# Docker Compose

Istruzioni per creare un ambiente di development WordPress con Docker and Docker Compose.

Nell'ambiente sono disponibli questi strumenti:

- [WordPress e WP CLI](https://hub.docker.com/_/wordpress/)
- [phpMyAdmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin/)
- [MySQL](https://hub.docker.com/_/mysql/)

Contenuto documento:

- [Requisiti](#requisiti)
- [Configurazione](#configurazione)
- [Installazione](#installazione)
- [Uso](#uso)
- [Deploy](#deploy-con-git)

## Requisiti

Assicurati di aver installato l'ultima versione di **Docker** e **Docker Compose**.

Nel file **docker-compose.yml** puoi cambiare l'IP in caso di container multipli.

Se usi linux assicurati di [add your user to the `docker` group](https://docs.docker.com/install/linux/linux-postinstall/#manage-docker-as-a-non-root-user).

## Configurazione

E' possibile modificare il file `.env` per cambiare l'IP e la porta di default di Wordpress, la MySQL root password e il nome del db WordPress.

## Installazione

Apri il terminale ed entra nella cartella docker `cd docker` dove è contenuto il file `docker-compose.yml` ed eseguilo con questo comando:

```
docker-compose up
```

Questo comando crea i containers docker e tre nuove directory dove vengono montati i volumi dei container.

* `wp-data` – usato per salvare ed esportare i dumps del database
* `wp-app` – usato per i file dell' applicazione WordPress 
* `db-data` – usato per i data-file del database

I containers ora sono raggiungibili agli indirizzi IP configurati.
Di default sono `http://127.0.0.1:8080` e `http://127.0.0.1:8081`

Puoi anche inserire un dominio nel tuo file hosts 

## Uso

### Partenza containers

Puoi far partire i containers con il comando `up` in modalità daemon (altrimenti aggiungendo `-d` come argomento) o usando il comando `start`:

```
docker-compose start
```

### Stoppare containers

```
docker-compose stop
```

### Rimuovere containers

Per fermare e rimuovere tutti i containers usa il comando `down`:

```
docker-compose down
```

Usa `-v` se hai bisogno di rimuovere il volume del database, usato per avere il database persistente:

```
docker-compose down -v
```

### Progetto da sorgente esistente

* `wp-data` – qui devi aggiungere il dump del database
* `wp-app` – qui copia il tuo codice di WordPress già esistente

Ora puoi usare il comando `up` :

```
docker-compose up
```

Questo crea i containers e viene popolato il database con il dump dato. Puoi settare il tuo host cambiandolo nel database, o semplicemente sovrascriverlo in `wp-config.php` aggiungendo:

```
define('WP_HOME','http://wp-app.local');
define('WP_SITEURL','http://wp-app.local');
```

### Creazione dump del Database

Esegui questo comando per fare l'export del database
```
./export.sh
```

### Sviluppo del tema starter

Il volume caricato per il tema è una cartella indetro a docker, come configurata nel `docker-compose.yml`:

```
volumes:
  - ../:/var/www/html/wp-content/themes/theme-name
```

### Update WordPress image

Per aggiornare l'immagine di WordPress e quindi creare ambienti di sviluppo locale agiornati all'ultima release esegeuire questo comando nel terminale:

- `docker pull wordpress`

### phpMyAdmin

A questo indirizzo `http://127.0.0.1:8081` hai accesso al phpMyAdmin dopo aver fatto partire i containers.

Lo username di default è `root`, e la password è la stessa impostata nel file `.env`.

# Deploy con GIT

E' possibile fare il deploy tramite git dal repository che avete inizializzato nella cartella indetro a docker.

Tramite il comando: 
```
./deploy.sh
```
verranno controllati i git remote, i git branch, verrà controllato l'ambiente NVM e Node.js e verranno inizializzati i vari repository remoti sia su development che su production.

Il comando vi chiederà su quale ambiente eseguire l'init del delploy ed altre preferenze.

## Deploy ambiente development

Prima di procedere al deploy assicurarsi di installare Wordpress sul server di development `DEV_DOMAIN`, con il nome cartella uguale alla variabile `COMPOSE_PROJECT_NAME` nel file `.env`.

### Installazione

Il comando `./deploy.sh` controlla e crea se richiesto il remote e il branch git locale `dev`

### Comandi
- `git push dev dev` carica i file aggiornati all'ultimo commit del branch `dev` verso il server di development ed esegue il bundling del asset

Eseguire il push su remote dev di un branch diverso da dev non provocherà nessuna modifica sul server remoto

## Deploy ambiente production e staging

### Installazione

Il comando `./deploy.sh` controlla e crea se richiesto il remote e il branch git locale `prod` e `staging`.

### Comandi
- `git push prod staging` carica i file aggiornati all'ultimo commit del branch `staging` verso il server di production nella versione di staging ed esegue il bundling del asset
- - `git push prod prod` carica i file aggiornati all'ultimo commit del branch `prod` verso il server di production nella versione online ed esegue il bundling del asset