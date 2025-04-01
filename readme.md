# TWOW Starter Theme

![](screenshot.png)

## Contenuto documento

 1. [Under the hood](#under-the-hood)
 2. [Requisiti](#requisiti)
 3. [Uso](#uso)
 4. [Vite & WordPress](#vite-wordpress)
 5. [Istruzioni](#istruzioni)
 6. [Componenti installati](#componenti-installati)
 7. [Struttura](#struttura)
 8. [Come registrare i Blocks](#come-registrare-i-blocks)
 9. [Elenco Blocks](#elenco-blocks)
 10. [Come registrare i CPT](#come-registrare-i-cpt) 
 11. [Helper](#helper)
 12. [Library](#library) 
 13. [HTACCESS for Gzip](#htaccess-for-gzip) 
 14. [Docker](https://github.com/twowrepo/twow-starter-theme/tree/main/docker#docker-compose)
 15. [Deploy](https://github.com/twowrepo/twow-starter-theme/tree/main/docker#deploy-con-git)  
 
## Under the hood

- [ES6](https://github.com/lukehoban/es6features#readme) for JavaScript
- [SASS](http://sass-lang.com/) preprocessor for CSS following [SASS Guidelines](https://sass-guidelin.es/#the-7-1-pattern)
- [Vite.js](https://vitejs.dev/) to compile theme assets and provide live reload
- [Bem](https://getbem.com/) Methodology that helps you to create reusable components [CSS](https://getbem.com/naming/)

## Requisiti

* [Node](https://nodejs.org/) = 20 || >= 22

## Documentation

- [WordPress template hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/) Gerarchia templates 
- [WordPress Hook order](https://wp-kama.com/hooks/actions-order) Ordine di esecuzione hook di WordPress

## Uso

Prima, clona questo repo in una directory.

Quindi, esegui un install dentro la direcotry del tema con il seguente comando node : `npm install`

Lancia per il watch degli assets con : `npm run dev`

Per produzione, crea la tua compilazione con : `npm run build`

## Vite & WordPress
- Tutti gli assets statici usati in css e js (images, fonts, etc.) sono copiati come sono nella directory `dist` dalla directory `static`. Le altre immagini usate direttamente in php non vengono copiate.

## Istruzioni

### SCSS
- Nel scss file usa l'alias `@` per caricare dalla directory `static/`. es. : `background-image: url('@/img/logo.png');`    

### PHP
- I metodi di Extensions ed Helper sono racchiusi dentro il namespace `TST`. es. : `new TST\Template();` 

### JAVASCRIPT
- La variabile globale `window.siteVars` espone variabili utili all'interno di Javascript:
    - `ajaxUrl` (url chiamate ajax)
    - `query_vars` (variabili nella query)
    - `current_page` (pagina corrente)
    - `max_page` (numero massimo di paginazione)
    - `template_directory` (path assoluto del template)
    - `template_uri` (url per il template)
	- `template_path` (path relativo del template)
    - `template` (nome del template)
    - `theme_root` (path assoluto dei themes)
    - `home_full_vh` (altezza layout home in full viewport, impostando l'inizio dei blocchi dal top della pagina)
    - `locomotive_scroll` (abilita lo scroll smooth di Locomotive)


## Componenti installati

- [Bootstrap 5](https://getbootstrap.com/docs/5.3/getting-started/introduction/) Framework css/js
- [Choices.js](https://github.com/Choices-js/Choices) Select box/text input plugin
- [GSAP](https://gsap.com/docs/v3/GSAP/) Animation plugin
- [Isotope layout](https://isotope.metafizzy.co/layout) Filter & sort magical layouts
- [Simplebar](https://github.com/Grsmto/simplebar) Replace the browser's default scrollbar
- [Swiper](https://github.com/nolimits4web/swiper) Modern mobile touch slider


## Struttura
```
assets
| - dist
|| - css
|| - js
| - src
|| - js
||| - components
||| - utils
|| - scss
configure
extensions
helper
partials
static
| - fonts
| - img
templates
| - blocks
- xhr.php : File da richiamare direttamente per chiamate AJAX
```



## Come registrare i CPT

- La registrazione dei CPT avviene con la creazione di un file in : `/cpt` 
- Il file deve essere creato sulla base dell'esempio già presente `snippet.php`
- Si deve modificare il nome della classe, le variabili `POST_TYPE_NAME` e `TAXONOMY_NAME` e configurare gli args in base alle necessità

## HELPER

Elenco delle funzionalità del tema base

### Template

**How to use :** `new TW\Template();` per avere l'object.

**Methods :**
: get_glob_files : `->get_glob_files($path);` per array di files in $path ricercato.
: load_template_part : `->load_template_part(...);` per caricare template in stringa.
: get_template_file_uri
: format_date
: replace_placeholder : sostituisce placeholders `{...}` con valori in `$_GET` es.: `{email}` viene sostituito da `$_GET["email"]`.
: theme_url : restituisce url dalla directory root del template, es.:  `->theme_url( 'xhr.php' )`

### Network

**How to use :** `new TW\Network();` per avere l'object.

**Methods :**
 - get_client_ip : `->get_client_ip();` per ricevere l'ip del client.
 - get_request : `->get_request(...);` per recuperare la $_REQUEST ricercata della pagina.

### Tracking

Questo helper registra il gclid o le sue varianti nel cookie `tw-gclid` per essere utilizzato in ogni parte del template.


### Navwalker

Classe NavWalker per estendere menu Wordpress con Bootstrap e Megamenu

### Menu

**How to use :** `new TW\Menu();` per avere l'object.

**Methods :**

- get_nav_menu_items : `->get_nav_menu_items();` per ricevere gli item di un menu.
- count_menu_item_children : `->count_menu_item_children();` per avere il numero di item children
- is_active_nav_menu_item : `->is_active_nav_menu_item();` per individuare item corrente.

### WP

Rimuove prefisso "Categoria:", Archivio:" da pagine tassonomy

**How to use :** `new TW\WP();` per avere l'object.

**Methods :**

- get_title : `->get_title();`
- get_description : `->get_description();`
- get_entry_date
- entry_date
- term_list
- get_post_categories
- post_categories


### Media

Permette il caricamento di media file svg

### Tanslate

Si occupa di caricare i file di traduzione presenti nella cartella `languages` e implementa i metodi per la traduzione delle stringhe compatibile con Polylang

**How to use :** 
- `<?php \TST\trad___('Assistenza'); ?>` per avere la traduzione.
- `<?php \TST\trad___e('Assistenza'); ?>` per stampare la traduzione.


## EXTENSIONS

Sezione dedicata ad estendere funzionalità dei plugin

### ACF

#### Options

L'estensione registra una pagina option generale (TST Theme Settings) ed una per lingua se il plugin polylang è installato.

l'ID per le option generale è lo standard `option`, quello per le pagine in lingua è composto dal prefisso `option` + il codice lingua, es.: `option-it`

#### Field Group

I Gruppi di campi possono essere caricati in due formati differenti, JSON e PHP.

***JSON***

Nella directory `extensions/acf/json` vengono salvati, caricati e aggiornati i files JSON che genera il plugin.
Modificando direttamente il JSON si dovrà andare a sincronizzare il gruppo di campi nella gestione del plugin.

***PHP***

Nella directory `extensions/acf/fields` è possibile caricare i file PHP con la registrazione dei campi tramite il metodo `acf_add_local_field_group()`.
I gruppi di campi gestiti in questo modo non saranno visibile nella gestione del plugin.

#### Blocks

***Come registrare i Blocks***

- La registrazione dei blocchi avviene con la creazione di un file in : `/extensions/acf/blocks` ; tramite la funzione : `\TST\ACF::register_block()`.
- I Campi dei Blocchi possono essere gestiti con php inserendo un file php in : `/extensions/acf/blocks/fields` e vengono caricati automaticamente.
- I Campi dei Blocchi possono essere gestiti con file json in : `/extensions/acf/blocks/json` dove vengono salvati e caricati automaticamente.
- Il template del block dovrà essere posizionato in : `/templates/blocks/[nome blocco]`.
- Il template può contenere il scss : `_[nome blocco].scss`; e dovrà essere caricato dal file js se deve essere aggiunto nell'asset.
- Il template può contenere il js : `[nome blocco].js`; e verrà caricato automaticamente nella pagina.

***Elenco Blocks preinstallati***

- Accordion
- Big text
- Hero 
- Hero slider
- Slider
- Slider card
- Offcanvas card
- Form
- Texts

### Gravityforms

L'estensione implementa due funzionalità per la precompilazione dei campi.

***Gclid***

Se il campo di tipo `hidden` ha come nome input di autocompilazione `gclid` verrà valorizzato con il valore del cookie registrato dall'helper `Tracking`

***Custom Post values***

Se il campo di tipo `select` ha come classe css una stringa che inizia per  `populate_cpt_` verrà valorizzata con i post_title del cpt inserito come ultimo elemento della classe, es.: `populate_cpt_prodotti`


Impostando i Field Values `include_cpt` o `exclude_cpt` con i titoli dei cpt separati da virgola, saranno inclusi o esclusi solamente i post elencati. (Questo permette avere select personalizzate per lo stesso form )


***Taxonomy values***

Se il campo di tipo `select` ha come classe css una stringa che inizia per  `populate_tax_` verrà valorizzata con i terms della taxonomy inserita come ultimo elemento della classe, es.: `populate_cpt_categoria`


Impostando i Field Values `include_tax` o `exclude_tax` con i nome dei term separati da virgola, saranno inclusi o esclusi solamente i term elencati. (Questo permette avere select personalizzate per lo stesso form )

### Yoast

L'estensione implementa due funzionalità per la gestione del breadcrumb e della sitemap.

***Breadcrumb***

Personalizza il contenitore e la struttura del breadcrumb e lo stampa nel hook del template `tst_before_content` tra l'header ed il content.

***Sitemap***
- Include le immagini dei custom post nella sitemap
- Include le immagini delle taxonomy nella sitemap
- Esclude pagine in lingua dalla sitemap se disabilitata option (Abilita traduzioni)

### Polylang

L'estensione implementa il language switcher e la registrazione delle stringhe



## LIBRARY

- Aq_Resize : Resize image on the fly.

## HTACCESS 

Configurazione `.htaccess` per l'uso degli assets compressi in gzip (.gz) e altre regole di sicurezza.

```
# GZIP SUPPORT
# enable the rewrite capabilities
RewriteEngine On

# prevents the rule from being overrided by .htaccess files in subdirectories
RewriteOptions InheritDownBefore

# provide a URL-path base (not a file-path base) for any relative paths in the rule's target
RewriteBase /

# GZIP
## allows you to have certain browsers uncompress information on the fly
AddEncoding gzip .gz
## serve gzip .css files if they exist and the client accepts gzip
RewriteCond %{HTTP:Accept-encoding} gzip
RewriteCond %{REQUEST_FILENAME}\.gz -s
RewriteRule ^(.*)\.css $1\.css\.gz [QSA]
## serve gzip .js files if they exist and the client accepts gzip
RewriteCond %{HTTP:Accept-encoding} gzip
RewriteCond %{REQUEST_FILENAME}\.gz -s
RewriteRule ^(.*)\.js $1\.js\.gz [QSA]
## serve gzip .html files if they exist and the client accepts gzip
RewriteCond %{HTTP:Accept-encoding} gzip
RewriteCond %{REQUEST_FILENAME}\.gz -s
RewriteRule ^(.*)\.html $1\.html\.gz [QSA]
## serve correct content types, and prevent mod_deflate double gzip
RewriteRule \.css\.gz$ - [T=text/css,E=no-gzip:1,E=is_gzip:1]
RewriteRule \.js\.gz$ - [T=text/javascript,E=no-gzip:1,E=is_gzip:1]
RewriteRule \.html\.gz$ - [T=text/html,E=no-gzip:1,E=is_gzip:1]
Header set Content-Encoding "gzip" env=is_gzip

# Blocco XMLRPC preventivo
<Files xmlrpc.php>
order deny,allow
deny from all
</Files>

# Esempio redirect
# Redirect 301 /old-page /new-page

```