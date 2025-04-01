#!/bin/bash

# Load environment variables
set -a && source .env && set +a

DIR_REPOS="repos"
REPO="${DIR_REPOS}/${COMPOSE_PROJECT_NAME}"

check_local_dev_remote() {
    DEV_REPO="home/${DEV_USER}/${REPO}"
    cd ".."
    echo "Controllo remote locale"
    if ! git remote | grep -q "^dev$"; then
        tput setaf 1;
        sleep 1
        echo -e "\n"
        echo "Remote dev non esiste, lo creo"
        sleep 1
        echo -e "\n"
        git remote add dev ssh://$DEV_USER@$DEV_HOST/$DEV_REPO
        tput setaf 2;
        echo "✅️ Remote dev creato!"
    else
        tput setaf 2;
        sleep 1
        echo -e "\n"
        echo "Remote dev esiste già"
    fi
    tput setaf 7;
    cd "docker"
}
check_local_dev_branch() {
    # cd ".."
    tput setaf 7;
    echo "Controllo branch locale"
    if ! git rev-parse --verify dev > /dev/null 2>&1; then
        tput setaf 3;
        sleep 1
        echo -e "\n"
        read -p "Branch dev non esiste, vuoi che lo creo? (yes/no) " yn

        case $yn in 
            yes ) tput setaf 2; echo ok, procedo!;;
            no ) echo exiting...;;
            * ) echo invalid response, skipping;;
        esac

        if [[ $yn == yes ]]; then

        git checkout -b dev

        sleep 1
        echo -e "\n"
        tput setaf 2;
        echo "✅️ Remote dev creato!"
        fi
    else
        tput setaf 3;
        sleep 1
        echo -e "\n"
        echo "Branch dev esiste già"
    fi
    tput setaf 7;
    # cd "docker"
    # exit 0
}

# Funzione per verificare se tutte le variabili dell'ambiente sono valorizzate
check_env_variables() {
    if [ -z "$DEV_USER" ] || [ -z "$DEV_HOST" ] || [ -z "$PROD_USER" ] || [ -z "$PROD_HOST" ]; then
        tput setaf 1;
        echo "Errore: Una o più variabili dell'ambiente (DEV_USER, DEV_HOST, PROD_USER, PROD_HOST) non sono valorizzate."
        echo "Assicurati che DEV_USER, DEV_HOST, PROD_USER e PROD_HOST siano valorizzati nel file .env."
        exit 1
    fi

    # Aggiunto controllo per le variabili aggiuntive
    if [ -z "$COMPOSE_PROJECT_NAME" ] || [ "$COMPOSE_PROJECT_NAME" == "test" ]; then
        tput setaf 1;
        echo "Errore: COMPOSE_PROJECT_NAME è vuota o impostata su 'test'."
        echo "Assicurati che COMPOSE_PROJECT_NAME sia valorizzata e diversa da 'test'."
        exit 1
    fi

    if [ -z "$DEV_PROJECT_DIR" ]; then
        tput setaf 1;
        echo "Errore: DEV_PROJECT_DIR non è valorizzata."
        exit 1
    fi

    if [ -z "$PROD_DOMAIN" ]; then
        tput setaf 1;
        echo "Errore: PROD_DOMAIN non è valorizzata."
        exit 1
    fi
    tput setaf 2;
    echo "✅️ Variabili valorizzate!"
    tput setaf 7;
    echo -e "\n"
    sleep 1
}

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


# Funzione per eseguire comandi remoti con password tramite expect
execute_remote_with_password() {
    REMOTE_USER=$1
    REMOTE_HOST=$2
    COMMAND=$3

    # Richiedi la password SSH all'utente
    read -sp "Inserisci la password SSH: " SSH_PASSWORD
    echo

    # Usa expect per inviare la password SSH
    /usr/bin/expect <<EOF
    set timeout 20
    spawn ssh $REMOTE_USER@$REMOTE_HOST $COMMAND
    expect "password:"
    send "$SSH_PASSWORD\r"
    expect eof
EOF
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

# Funzione per controllare la presenza della chiave SSH
check_ssh_key() {
    echo "Verifica della connessione SSH con la chiave predefinita..."
    
    ssh -o BatchMode=yes -o ConnectTimeout=5 "$USER@$HOST" exit
    
    if [ $? -eq 0 ]; then
        echo "Connessione SSH riuscita con l'host $HOST."
        return 0
    else
        echo "Connessione SSH fallita con l'host $HOST."
        return 1
    fi
}

# Funzione per installare NVM su remoto
install_nvm_remote() {
    execute_remote "$USER" "$HOST" "
    sleep 1
    echo -e '\n'
    echo 'Installazione di NVM su $HOST...'
    if [ ! -d \"\$HOME/.nvm\" ]; then
        curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash
        . \$HOME/.nvm/nvm.sh
        echo '✅️ NVM installato!'
    else
        echo 'NVM già installato!'
    fi
    exit
    "
}

# Funzione per installare Node.js su remoto
install_node_remote() {
    NODE_VERSION=$NODE
    COMMAND="
    sleep 1
    echo -e '\n'
    echo 'Installazione di Node.js su $HOST...'
    echo -e '\n'
    if ! command -v node &> /dev/null || [[ \$(node -v) != v${NODE_VERSION} ]]; then
        echo 'Node.js non è installato o non è la versione ${NODE_VERSION}. Procedo con installazione...'
        nvm install ${NODE_VERSION}
        nvm alias default ${NODE_VERSION}
        echo '✅️ Node.js installato!'
    else
        echo 'Node.js è già installato con la versione ${NODE_VERSION}.'
    fi
    exit  # Uscita dalla sessione SSH dopo installazione di Node.js
    "
    execute_remote "$USER" "$HOST" "$COMMAND"
}

# Funzione per configurare il repository e l'hook post-receive in dev
configure_repo_and_hook_dev() {

    tput setaf 3;
    sleep 1
    echo -e "\n"
    read -p "Vuoi configurare il repo remoto di dev? (yes/no) " yn

    case $yn in 
        yes ) tput setaf 2; echo ok, procedo!;;
        no ) echo exiting...;;
        * ) echo invalid response, skipping;;
    esac

    if [[ $yn == no ]]; then
        exit 0
    fi
    # Esegui le funzioni per installare NVM e Node.js, quindi configura il repository e l'hook
    # install_nvm_remote
    # install_node_remote

    HOOK_FILE="${REPO}/hooks/post-receive"

    DEV_WORK_DIR="${REMOTE_PROJECT_DIR}/wp-content/themes/${COMPOSE_PROJECT_NAME}"

REMOTE_COMMANDS=$(cat <<EOF

# Imposta il percorso del repository e della directory di lavoro
DEV_REPO="\$HOME/$REPO"
DEV_WORK_DIR="\$HOME/$DEV_WORK_DIR"

echo -e "\n"

echo "La directory HOME è: \$HOME"
echo "La repository è: $DEV_REPO"
echo "La work dir è: $DEV_WORK_DIR"

if [ ! -d "$DIR_REPOS" ]; then
  echo "$DIR_REPOS non esiste, la creo."
  mkdir -p $DIR_REPOS
else
  echo "La dir repos esiste"  
fi

if [ ! -d "$DEV_REPO" ]; then
  echo "Creazione del repository bare..."
  git init --bare "$DEV_REPO"
  echo "Repo ${COMPOSE_PROJECT_NAME} creato" > $DEV_REPO/description
else
  echo "Il repo esiste" 
fi

echo -e "\n"

echo "Configurazione Hook post-receive..."

if [ ! -f $HOOK_FILE ]; then
    echo "Hook ${HOOK_FILE} non esiste, lo creo."
    touch $HOOK_FILE
else
    echo "Hook già esistente"
fi

cat > "${HOOK_FILE}" <<'HOOK'
#!/bin/bash

while read oldrev newrev ref; do

    if [[ "\$ref" == "refs/heads/dev" ]]; then

        if [[ ! -d "\$HOME/$DEV_WORK_DIR" ]]; then
            echo "la working dir non esiste, la creo."
            mkdir -p "\$HOME/$DEV_WORK_DIR"
        fi

        echo "Push ricevuto! Deploying branch: ${ref}..."
        git --work-tree="\$HOME/$DEV_WORK_DIR" --git-dir="\$HOME/$DEV_REPO" checkout -f dev

        cd \$HOME/$DEV_WORK_DIR
        npm install
        npm run dev:remote

   else
       echo "Non hai selezionato il branch dev. Skipping."
   fi
done
HOOK


chmod ug+x \$HOME/$HOOK_FILE
exit
EOF
)

    execute_remote "$USER" "$HOST" "$REMOTE_COMMANDS"
}

configure_repo_and_hook_prod() {
    REMOTE_COMMANDS=""

    execute_remote "$USER" "$HOST" "$REMOTE_COMMANDS"
}

tput setaf 7;
echo -e "\n"
echo "Benvenuto nella procedura di installazione repo deploy."
sleep 1
echo -e "\n"
sleep 1
echo -e "\n"
echo "Eseguo controllo variabili env."
sleep 1
echo -e "\n"
# Check variabili
check_env_variables

tput setaf 7;
# Esegui la selezione dell'ambiente
select_environment

sleep 1
echo -e "\n"

tput setaf 2;
# Ora puoi usare la variabile ENVIRONMENT_CHOICE per eseguire altre operazioni
echo "L'ambiente scelto è: $ENVIRONMENT_CHOICE"

sleep 1
echo -e "\n"
tput setaf 7;


if [ "$ENVIRONMENT_CHOICE" == "prod" ]; then
    tput setaf 1;
    echo "L'ambiente $ENVIRONMENT_CHOICE non è ancora operativo"
    # check_local_prod_remote
    exit 1
fi


echo -e "\n"
echo "Connessione SSH su ${ENVIRONMENT_CHOICE} per verificare/installare nvm/node git repo(bare)/hook(pubblicazione)."



# Esegui configure_repo_and_hook_dev solo se l'ambiente è "dev"
if [ $ENVIRONMENT_CHOICE == "dev" ]; then
sleep 1
echo -e "\n"
tput setaf 7;
echo "Controllo repository locale"
sleep 1
echo -e "\n"
check_local_dev_remote
sleep 1
echo -e "\n"
check_local_dev_branch
sleep 1
echo -e "\n"  
configure_repo_and_hook_dev
fi

echo -e "\n"
echo "Operazioni completate con successo!"