#!/bin/bash

# Load environment variables
set -a && source .env && set +a

DIR_REPOS="repos"

echo -e "\n"
echo "Benvenuto nella procedura di installazione repo dev.\n"
echo -e "\n"
echo "Connessione SSH su dev.\n"

DEV_REPO="${DIR_REPOS}/${COMPOSE_PROJECT_NAME}"

HOOK_FILE="${DEV_REPO}/hooks/post-receive"

DEV_WORK_DIR="${DEV_PROJECT_DIR}/${COMPOSE_PROJECT_NAME}/wp-content/themes/${COMPOSE_PROJECT_NAME}"

REMOTE_COMMANDS=$(cat <<EOF

# Imposta il percorso del repository e della directory di lavoro
DEV_REPO="\$HOME/$DEV_REPO"
DEV_WORK_DIR="\$HOME/$DEV_WORK_DIR"

echo $DEV_REPO
echo $DEV_WORK_DIR

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




echo "Configurazione dell'hook post-receive... ${HOOK_FILE}"

if [ ! -f $HOOK_FILE ]; then
    echo "Hook ${HOOK_FILE} non esiste, lo creo."
    touch $HOOK_FILE
else
    echo "L'hook esiste"
fi

echo "La directory HOME è: \$HOME"

cat > "${HOOK_FILE}" <<'HOOK'
#!/bin/bash


#git --work-tree="\$HOME/$DEV_WORK_DIR" --git-dir="\$HOME/$DEV_REPO" checkout -f dev

while read oldrev newrev ref; do
    echo "Riferimento ricevuto: \$ref"
    
   
    
    if [[ "\$ref" == "refs/heads/dev" ]]; then

        if [[ ! -d "\$HOME/$DEV_WORK_DIR" ]]; then
            echo "la working dir non esiste, la creo."
            mkdir -p "\$HOME/$DEV_WORK_DIR"
        fi

        echo "Push ricevuto! Deploying branch: ${ref}..."
        git --work-tree="\$HOME/$DEV_WORK_DIR" --git-dir="\$HOME/$DEV_REPO" checkout -f dev

        cd \$HOME/$DEV_WORK_DIR
        npm install
        npm run dev

   else
       echo "Non hai selezionato il branch dev. Skipping."
   fi
done
HOOK


chmod ug+x \$HOME/$HOOK_FILE

EOF
)


# Esegui i comandi sul server remoto
ssh "$DEV_USER@$DEV_HOST" "$REMOTE_COMMANDS"

echo "Configurazione completata con successo!"

exit