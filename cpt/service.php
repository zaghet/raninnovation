<?php 
namespace TST;
if(!defined('ABSPATH') ) {
    exit; // Exit if accessed directly
}

if( ! class_exists( '\TST\ServicePostType' ) ) {
    class ServicePostType extends Main {
        const POST_TYPE_NAME    = 'service';
        const TAXONOMY_NAME     = TST_PREFIX.'service_category';

        public function __construct() {
            add_action('init','\TST\ServicePostType::RegisterTaxonomy');
            add_action('init','\TST\ServicePostType::RegisterPostType');
            add_action('add_meta_boxes', array($this, 'aggiungi_metabox_colore_sfondo'));
            add_action('add_meta_boxes', array($this, 'aggiungi_metabox_colore_testo')); // Aggiunto per il colore del testo
            add_action('save_post', array($this, 'salva_colore_sfondo'));
            add_action('save_post', array($this, 'salva_colore_testo')); // Aggiunto per salvare il colore del testo
            add_shortcode('tst_snippet','\TST\ServicePostType::getSnippet');
        }

        // Metabox per il colore di sfondo
        public function aggiungi_metabox_colore_sfondo() {
            add_meta_box(
                'sfondo_colore',               // ID del metabox
                'Colore di sfondo',            // Titolo del metabox
                array($this, 'campo_colore_sfondo'), // Funzione di callback
                self::POST_TYPE_NAME,          // Slug del custom post type
                'side',                        // Posizione (sidebar)
                'default'                      // Priorità
            );
        }

        public function campo_colore_sfondo($post) {
            // Recupera il colore di sfondo già salvato (se esiste)
            $colore_sfondo = get_post_meta($post->ID, '_sfondo_colore', true);
            ?>
            <label for="sfondo_colore">Scegli il colore di sfondo:</label>
            <select name="sfondo_colore" id="sfondo_colore">
                <option value="#FFBC00" <?php selected($colore_sfondo, '#FFBC00'); ?>>Yellow</option>
                <option value="#262626" <?php selected($colore_sfondo, '#262626'); ?>>Black</option>
                <option value="#DEDEDF" <?php selected($colore_sfondo, '#DEDEDF'); ?>>Grey</option>
            </select> 
            <?php
        }

        // Metabox per il colore del testo
        public function aggiungi_metabox_colore_testo() {
            add_meta_box(
                'colore_testo',                // ID del metabox
                'Colore del testo',            // Titolo del metabox
                array($this, 'campo_colore_testo'), // Funzione di callback
                self::POST_TYPE_NAME,          // Slug del custom post type
                'side',                        // Posizione (sidebar)
                'default'                      // Priorità
            );
        }

        public function campo_colore_testo($post) {
            // Recupera il colore del testo già salvato (se esiste)
            $colore_testo = get_post_meta($post->ID, '_colore_testo', true);
            ?>
            <label for="colore_testo">Scegli il colore del testo:</label>
            <select name="colore_testo" id="colore_testo">
                <option value="black" <?php selected($colore_testo, 'black'); ?>>Black</option>
                <option value="white" <?php selected($colore_testo, 'white'); ?>>White</option>
            </select>
            <?php
        }

        // Funzione per salvare il colore di sfondo
        public function salva_colore_sfondo($post_id) {
             // Verifica se è un salvataggio valido
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

            // Salva il valore del colore di sfondo
            if (isset($_POST['sfondo_colore'])) {
                update_post_meta($post_id, '_sfondo_colore', sanitize_hex_color($_POST['sfondo_colore']));
            }
        }

        // Funzione per salvare il colore del testo
        public function salva_colore_testo($post_id) {
            // Verifica se è un salvataggio valido
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

            // Salva il valore del colore del testo
            if (isset($_POST['colore_testo'])) {
                update_post_meta($post_id, '_colore_testo', sanitize_text_field($_POST['colore_testo']));
            }
        }

    // public static function RegisterTaxonomy(){
    //     $args = [
    //         'description'           => __('Categorie di ', 'tst' ) . self::POST_TYPE_NAME,
    //         'public'                => FALSE,
    //         'publicly_queryable'    => FALSE,
    //         'hierarchical'          => FALSE,
    //         'show_ui'               => TRUE,
    //         'show_in_menu'          => TRUE,
    //         'show_in_nav_menus '    => FALSE,
    //         //'show_in_rest'          => FALSE,
    //         //'rest_base'             => 'STRING',
    //         //'rest_namespace'        => 'STRING',
    //         //'rest_controller_class' => 'STRING',
    //         'show_tagcloud'         => FALSE,
    //         'show_in_quick_edit'    => TRUE,
    //         'show_admin_column'     => true,
    //         'meta_box_cb'           => 'post_categories_meta_box',
    //         //'meta_box_sanitize_cb'    => 'CALLABLE',
    //         'update_count_callback' => '_update_post_term_count',
    //         'query_var'             => true,
    //         'rewrite'               => FALSE,
    //         /*'default_term'          => [
    //             'name'                  => __('Thank You Messages','tst'),
    //             'slug'                  => 'thank-you',
    //             'description'           => __('Positively responding to a user request','tst'),
    //             ],*/
    //         'labels'                => [
    //             'name'                          => _x('Categorie','taxonomy general name','tst'),
    //             'singular_name'                 => _x('Categoria','taxonomy singular name','tst'),
    //             'search_items'                  => __('Cerca categoria','tst'),
    //             'popular_items'                 => __('Categorie popolari','tst'),
    //             'all_items'                     => __('Tutte le categorie','tst'),
    //             'parent_item'                   => NULL,
    //             'parent_item_colon'             => NULL,
    //             'edit_item'                     => __('Edit categoria','tst'),
    //             'update_item'                   => __('Update categoria','tst'),
    //             'add_new_item'                  => __('Add New categoria','tst'),
    //             'new_item_name'                 => __('New categoria','tst'),
    //             'separate_items_with_commas'    => __('Separate categoria with commas','tst'),
    //             'add_or_remove_items'           => __('Add or remove categorie','tst'),
    //             'choose_from_most_used'         => __('Choose from the most used categorie','tst'),
    //             'not_found'                     => __('No categorie found.','tst'),
    //             'menu_name'                     => __('Categorie','tst'),
    //         ],
    //     ];
    //     register_taxonomy(self::TAXONOMY_NAME,[self::POST_TYPE_NAME],$args);
    // }

    // Funzione per registrare il custom post type
    public static function RegisterPostType(){
        $args = [
            'description'           => __('Servizi','tst'),
            'public'                => TRUE,
            'publicly_queryable'    => TRUE,
            'hierarchical'          => FALSE,
            'exclude_from_search'   => TRUE,
            'show_ui'               => TRUE,
            'show_in_menu'          => TRUE,
            'show_in_nav_menus'     => TRUE,
            'show_in_admin_bar'     => TRUE,
            'show_in_rest'          => TRUE,
            'menu_position'         => NULL,
            'menu_icon'             => 'dashicons-text',
            'capability_type'       => 'page',
            'supports'              => ['title','editor', 'excerpt', 'thumbnail'],
            'taxonomies'            => [self::TAXONOMY_NAME],
            'has_archive'           => TRUE,
            'rewrite'               => ['slug' => 'servizi', 'with_front' => FALSE],
            'query_var'             => TRUE,
            'can_export'            => TRUE,
            'delete_with_user'      => FALSE,
            'labels'                => [
                'name'                  => _x('Servizi','Post type general name','tst'),
                'singular_name'         => _x('Servizio','Post type singular name','tst'),
                'menu_name'             => _x('Servizi','Admin Menu text','tst'),
                'name_admin_bar'        => _x('Servizio','Add New on Toolbar','tst'),
            ],
        ];
        register_post_type(self::POST_TYPE_NAME, $args);
    }

    // Funzione per visualizzare il contenuto del custom post
    public static function getSnippet($atts) {
        $atts           = array_change_key_case((array) $atts, CASE_LOWER);
        $shortcode_atts = shortcode_atts([
            'id'    => NULL,
            'full'  => 'false',
        ],$atts);
        if(!isset($shortcode_atts['id'])) return NULL;
        $shortcode_atts['full'] = filter_var($shortcode_atts['full'], FILTER_VALIDATE_BOOLEAN);
        $snippet = get_post($shortcode_atts['id']);
        if($snippet){
            ob_start();
            $colore_sfondo = get_post_meta($snippet->ID, '_sfondo_colore', true);
            $colore_testo = get_post_meta($snippet->ID, '_colore_testo', true);
            ?>
            <div id="snippet-post-<?= $snippet->ID ?>" style="background-color: <?= esc_attr($colore_sfondo); ?>; color: <?= esc_attr($colore_testo); ?>;">
                <div class="snippet-title"><?= $snippet->post_title; ?></div>
                <div class="snippet-content"><?= $snippet->post_content; ?></div>
            </div>
            <?php
            return ob_get_clean();
        }
    }
}
new ServicePostType;
}
