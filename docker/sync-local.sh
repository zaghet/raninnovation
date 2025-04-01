#!/bin/bash

# Load environment variables
set -a && source .env && set +a

# Funzione per la selezione dell'ambiente
select_environment() {
    echo "Seleziona l'ambiente:"
    PS3="Inserisci il numero dell'ambiente: "
    select opt in "dev" "prod" "staging"; do
        case $opt in
            dev)
                USER="$DEV_USER"
                HOST="$DEV_HOST"
                ENVIRONMENT_CHOICE="dev"
                REMOTE_PROJECT_DIR="${DEV_PROJECT_DIR}/${COMPOSE_PROJECT_NAME}"
                echo "Hai selezionato l'ambiente di sviluppo (dev)."
                break
                ;;
            prod)
                USER="$PROD_USER"
                HOST="$PROD_HOST"
                ENVIRONMENT_CHOICE="prod"
                REMOTE_PROJECT_DIR="${PROD_PROJECT_DIR}"
                echo "Hai selezionato l'ambiente di produzione (prod)."
                break
                ;;
            staging)
                USER="$PROD_USER"
                HOST="$PROD_HOST"
                ENVIRONMENT_CHOICE="staging"
                REMOTE_PROJECT_DIR="${STAGING_PROJECT_DIR}"
                echo "Hai selezionato l'ambiente di produzione (staging)."
                break
                ;;
            *)
                echo "Scelta non valida. Riprova."
                ;;
        esac
    done
}

# Funzione per eseguire comandi remoti senza password (usando la chiave SSH)
execute_remote_with_key() {
    REMOTE_USER=$1
    REMOTE_HOST=$2
    COMMAND=$3

    # Esegui il comando SSH direttamente, usando la chiave SSH per l'autenticazione
    ssh -o StrictHostKeyChecking=no "$REMOTE_USER@$REMOTE_HOST" "$COMMAND"
}

# Funzione per eseguire comandi remoti generici (che utilizza la chiave SSH o la password a seconda della condizione)
execute_remote() {
    REMOTE_USER=$1
    REMOTE_HOST=$2
    COMMAND=$3

    # Controlla se la chiave SSH è presente
    #check_ssh_key
    #if [ $? -eq 0 ]; then
        # Se la chiave SSH è presente, esegui senza password
        execute_remote_with_key "$REMOTE_USER" "$REMOTE_HOST" "$COMMAND"
    #else
        # Se la chiave SSH non è presente, esegui con password
    #    execute_remote_with_password "$REMOTE_USER" "$REMOTE_HOST" "$COMMAND"
    #fi
}

remote_prefix() {
    REMOTE_TABLE_PREFIX=$(ssh ${USER}@${HOST} "cd \$HOME/$REMOTE_PROJECT_DIR && wp db prefix")
    echo "Prefisso delle tabelle remote: ${REMOTE_TABLE_PREFIX}"
}

# Funzione per installare Node.js su remoto
dump_database() {

    # Chiedi se continuare con il dump del db
    read -p "Vuoi procedere con il dump e la sincronizzazione del database? (s/n): " proceed
    if [[ $proceed != "s" ]]; then
        echo "Sincronizzazione annullata."
        return 1
    fi

    REMOTE_DUMP_FILE="./dump.sql"
    DUMP_COMMAND="
    echo -e '\n'
    echo 'Dump da $HOST...$COMPOSE_PROJECT_NAME'
    echo -e '\n'
    cd \$HOME/$REMOTE_PROJECT_DIR || exit 1

    # Recupera credenziali dal file wp-config.php
    REMOTE_DB_NAME=\$(wp config get DB_NAME)
    REMOTE_DB_USER=\$(wp config get DB_USER)
    REMOTE_DB_PASS=\$(wp config get DB_PASSWORD)

    # Debug: Stampa le variabili per verificare
    echo 'DB_NAME: '\$REMOTE_DB_NAME
    echo 'DB_USER: '\$REMOTE_DB_USER
    echo 'DB_PASS: '\$REMOTE_DB_PASS
 
    # Esegui il dump
    mysqldump -u\$REMOTE_DB_USER -p\$REMOTE_DB_PASS \$REMOTE_DB_NAME > ${REMOTE_DUMP_FILE}

    #wp db export ${REMOTE_DUMP_FILE}
    
    exit  # Uscita dalla sessione SSH
    "
    execute_remote "$USER" "$HOST" "$DUMP_COMMAND"

    download_dump

    
}

download_dump() {
    check_scp || return 1

    LOCAL_DUMP_FILE="../tmp/dump.sql"

    if [ ! -d "../tmp" ]; then
        mkdir "../tmp"
    fi

    REMOTE_DUMP_FILE="/home/${USER}/${REMOTE_PROJECT_DIR}/dump.sql"
    echo " Download del file dump dal server remoto... ${REMOTE_DUMP_FILE}"
    scp ${USER}@${HOST}:${REMOTE_DUMP_FILE} ${LOCAL_DUMP_FILE}
    echo -e '\n'
    if [[ -f ${LOCAL_DUMP_FILE} ]]; then
        tput setaf 2;
        echo "Download completato con successo!"
    else
        tput setaf 1;
        echo "Errore nel download del dump."
        return 1
    fi
    
    sleep 1
    echo -e '\n'
    tput setaf 7;

    COMMAND="
    echo -e '\n'
    echo 'Rimozione Dump da $HOST...$COMPOSE_PROJECT_NAME'
    echo -e '\n'
    rm -f ${REMOTE_DUMP_FILE}
    
    exit  # Uscita dalla sessione SSH
    "
    execute_remote "$USER" "$HOST" "$COMMAND"

    import_dump
}

import_dump() {
    LOCAL_DUMP_FILE="wp-content/themes/${COMPOSE_PROJECT_NAME}/tmp/dump.sql"
    LOCAL_CONTAINER="${COMPOSE_PROJECT_NAME}-wp-1"
    LOCAL_PATH="/var/www/html"
    OLD_DOMAIN="https://${DEV_DOMAIN}/${COMPOSE_PROJECT_NAME}"
    NEW_DOMAIN="http://${IP}:${PORT}"
    LOCAL_TABLE_PREFIX="wp_"
    LOCAL_LIB_MIGRATE=wp-content/themes/${COMPOSE_PROJECT_NAME}/library/Search-Replace-DB/srdb.cli.php

    if [ "${REMOTE_TABLE_PREFIX}" != "${LOCAL_TABLE_PREFIX}" ]; then
    echo -e "\n"
    sleep 1
    echo "Modifica del prefisso delle tabelle all'interno del container Docker..."
    docker exec -i ${LOCAL_CONTAINER} bash -c "sed -i 's/\`${REMOTE_TABLE_PREFIX}/\`${LOCAL_TABLE_PREFIX}/g' ${LOCAL_PATH}/${LOCAL_DUMP_FILE}"
    echo -e "\n"
    tput setaf 2;
    echo "Prefisso delle tabelle aggiornato nel dump SQL."
    fi
    tput setaf 7;
    echo -e "\n"
    sleep 1
    echo "Importazione del database nel container tramite WP-CLI..."
    echo -e "\n"
    tput setaf 2;
    docker exec ${LOCAL_CONTAINER} wp db import ${LOCAL_PATH}/${LOCAL_DUMP_FILE} --allow-root
    echo -e "\n"
    tput setaf 7;
    echo -e "\n"
    sleep 1
    echo "Search & Replace del dominio nel db locale..."
    tput setaf 2;
    echo -e "\n"
    # Con la wp-cli ----------------------------------------------------------------
    docker exec ${LOCAL_CONTAINER} wp search-replace ${OLD_DOMAIN} ${NEW_DOMAIN} wp_posts wp_postmeta --allow-root 
    docker exec ${LOCAL_CONTAINER} wp db query "
    UPDATE wp_options 
    SET option_value = REPLACE(option_value, '${OLD_DOMAIN}', '${NEW_DOMAIN}') 
    WHERE option_name IN ('siteurl', 'home');
    " --allow-root 
    # Con la Search-Replace-DB ----------------------------------------------------------------
    # docker exec ${LOCAL_CONTAINER} php ${LOCAL_LIB_MIGRATE} -h db -n ${DB_NAME} -u root -p "${DB_ROOT_PASSWORD}" -s "${OLD_DOMAIN}" -r "${NEW_DOMAIN}" --allow-root 
    tput setaf 7;
    echo -e "\n"
    echo "Reset role wp..."
    tput setaf 2;
    docker exec ${LOCAL_CONTAINER} wp role reset administrator editor author contributor subscriber --allow-root
    tput setaf 7;
    echo -e "\n"
    echo "Set administrator wp..."
    tput setaf 2;
    docker exec ${LOCAL_CONTAINER} wp user add-role 1 'administrator' --allow-root
    tput setaf 7;
    echo -e "\n"
    sleep 1
    echo "Reset permalink..."
    tput setaf 2;
    docker exec ${LOCAL_CONTAINER} wp rewrite flush --allow-root
    tput setaf 7;
    # echo -e "\n"
    # sleep 1
    # echo "Rimozione dump locale..."
    # rm -f ../dump.sql
    # tput setaf 2;
    # echo "Dump locale rimosso."
    # echo -e "\n"
    # tput setaf 7;
}

# Controllo della presenza di rsync in locale
check_rsync() {
    if ! command -v rsync >/dev/null 2>&1; then
        echo "Errore: rsync non è installato sul sistema locale."
        return 1  # Esci dalla funzione con errore
    fi
}
check_scp() {
    if ! command -v scp >/dev/null 2>&1; then
        echo "Errore: scp non è installato sul sistema locale."
        return 1  # Esci dalla funzione con errore
    fi
}

import_plugins() {
    check_rsync || return 1  # Se rsync non è disponibile, esce dalla funzione

    LOCAL_PLUGIN_PATH="wp-content/plugins"
    LOCAL_CONTAINER="${COMPOSE_PROJECT_NAME}-wp-1"
    LOCAL_PATH="/var/www/html"
    REMOTE_PLUGIN_PATH="/home/${USER}/${REMOTE_PROJECT_DIR}/${LOCAL_PLUGIN_PATH}"

    # Chiedi se continuare con il download dei plugin
    read -p "Vuoi scaricare i plugin dal server remoto? (s/n): " proceed
    if [[ $proceed != "s" ]]; then
        echo "Sincronizzazione dei plugin annullata."
        return 1
    fi

    check_rsync || return 1  # Se rsync non è disponibile, esce dalla funzione

    # Chiedi come sincronizzare (solo differenze o sincronizzazione completa)
    read -p "Vuoi sincronizzare solo plugin mancanti? (s/n): " sync_diff
    if [[ $sync_diff == "s" ]]; then
        SYNC_ONLY_DIFF=true
    else
        SYNC_ONLY_DIFF=false
    fi

    # # Controllo della presenza di rsync nel container
    # if ! docker exec ${LOCAL_CONTAINER} bash -c "command -v rsync >/dev/null"; then
    #     echo "==> Installazione di rsync nel container..."
    #     docker exec ${LOCAL_CONTAINER} apt-get update && docker exec ${LOCAL_CONTAINER} apt-get install -y rsync
    # fi

    # Sincronizzazione
    echo "Sincronizzazione della cartella plugin direttamente nel container Docker..."
    if [ "${SYNC_ONLY_DIFF}" = true ]; then
        echo "==> Sincronizzazione solo delle differenze..."

        # Recupera la lista delle cartelle di primo livello sul server remoto
        echo "==> Recupero della lista dei plugin in remoto...${REMOTE_PLUGIN_PATH}"
        REMOTE_DIRS=$(ssh ${USER}@${HOST} "ls -1 ${REMOTE_PLUGIN_PATH}")

        # Recupera la lista delle cartelle di primo livello nel container locale
        echo "==> Recupero della lista dei plugin in locale..."
        LOCAL_DIRS=$(docker exec ${LOCAL_CONTAINER} bash -c "ls -1 ${CONTAINER_PLUGIN_PATH}")

        # Confronta e scarica solo le cartelle mancanti
        echo "==> Scarico solo i plugin mancanti..."
        for DIR in ${REMOTE_DIRS}; do
            if [[ ! " ${LOCAL_DIRS} " =~ " ${DIR} " ]]; then
                echo "Scarico plugin ${DIR}..."
                mkdir wp-app/${LOCAL_PLUGIN_PATH}/${DIR}
                rsync -e 'ssh -o StrictHostKeyChecking=no' -avzh --progress --stats ${USER}@${HOST}:${REMOTE_PLUGIN_PATH}/${DIR}/ wp-app/${LOCAL_PLUGIN_PATH}/${DIR}/
            else
                echo "Il plugin ${DIR} è già presente. Salto il download."
            fi
        done
    else
        echo "Sincronizzazione completa (incluso eliminazione dei file locali non presenti nel remoto)..."
        
        rsync -e 'ssh -o StrictHostKeyChecking=no' -avzh --progress --stats --delete ${USER}@${HOST}:${REMOTE_PLUGIN_PATH}/ wp-app/${LOCAL_PLUGIN_PATH}/
    fi
}

download_media() {
    check_rsync || return 1  # Se rsync non è disponibile, esce dalla funzione

    LOCAL_UPLOADS_PATH="wp-content/uploads"
    LOCAL_CONTAINER="${COMPOSE_PROJECT_NAME}-wp-1"
    LOCAL_PATH="/var/www/html"
    REMOTE_UPLOADS_PATH="/home/${USER}/${REMOTE_PROJECT_DIR}/${LOCAL_UPLOADS_PATH}"

    # Chiedi se procedere
    read -p "Vuoi scaricare i media dal server remoto? (s/n): " proceed
    if [[ $proceed != "s" ]]; then
        echo "Scaricamento dei media annullato."
        return 0
    fi

    echo "Verifica dei media nella cartella uploads..."

    # Chiedi come sincronizzare
    read -p "Vuoi scaricare solo i file mancanti? (s/n): " sync_diff
    if [[ $sync_diff == "s" ]]; then
        SYNC_ONLY_DIFF=true
    else
        SYNC_ONLY_DIFF=false
    fi

    # Controllo della presenza di rsync nel container
    # if ! docker exec ${LOCAL_CONTAINER} bash -c "command -v rsync >/dev/null"; then
    #     echo "rsync non è installato nel container. Installazione in corso..."
    #     docker exec ${LOCAL_CONTAINER} apt-get update && docker exec ${LOCAL_CONTAINER} apt-get install -y rsync
    # fi

    # Esecuzione del download
    if [ "${SYNC_ONLY_DIFF}" = true ]; then
        echo "Scarico solo i file mancanti..."
        rsync -e 'ssh -o StrictHostKeyChecking=no' -avzh --progress --stats --ignore-existing ${USER}@${HOST}:${REMOTE_UPLOADS_PATH}/ wp-app/${LOCAL_UPLOADS_PATH}/
    else
        echo "Scarico tutti i file, inclusi quelli esistenti..."
        rsync -e 'ssh -o StrictHostKeyChecking=no' -avzh --progress --stats ${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_UPLOADS_PATH}/ wp-app/${LOCAL_UPLOADS_PATH}/
    fi

    echo "Download Media completato con successo!"
}

download_language() {
    check_rsync || return 1  # Se rsync non è disponibile, esce dalla funzione

    LOCAL_LANG_PATH="wp-content/languages"
    LOCAL_CONTAINER="${COMPOSE_PROJECT_NAME}-wp-1"
    LOCAL_PATH="/var/www/html"
    REMOTE_LANG_PATH="/home/${USER}/${REMOTE_PROJECT_DIR}/${LOCAL_LANG_PATH}"

    # Chiedi se procedere
    read -p "Vuoi scaricare le language dal server remoto? (s/n): " proceed
    if [[ $proceed != "s" ]]; then
        echo "Scaricamento dei language annullato."
        return 0
    fi

    echo "Verifica delle language..."

    # Chiedi come sincronizzare
    read -p "Vuoi scaricare solo i file mancanti? (s/n): " sync_diff
    if [[ $sync_diff == "s" ]]; then
        SYNC_ONLY_DIFF=true
    else
        SYNC_ONLY_DIFF=false
    fi

    # Esecuzione del download
    if [ "${SYNC_ONLY_DIFF}" = true ]; then
        echo "Scarico solo i file mancanti..."
        rsync -e 'ssh -o StrictHostKeyChecking=no' -avzh --progress --stats --ignore-existing ${USER}@${HOST}:${REMOTE_LANG_PATH}/ wp-app/${LOCAL_LANG_PATH}/
    else
        echo "Scarico tutti i file, inclusi quelli esistenti..."
        rsync -e 'ssh -o StrictHostKeyChecking=no' -avzh --progress --stats ${USER}@${HOST}:${REMOTE_LANG_PATH}/ wp-app/${LOCAL_LANG_PATH}/
    fi

    echo "Download Language completato con successo!"
}

select_environment

if [ $ENVIRONMENT_CHOICE == "dev" ]; then

    remote_prefix

    dump_database

    import_plugins

    download_media

    download_language

fi

echo "Operazione completata con successo!"