<?php 
namespace TST;
if(!defined('ABSPATH') ) {
    exit; // Exit if accessed directly
}

if( ! class_exists( '\TST\ServicePostType' ) ) {
class ServicePostType extends Main {
    const POST_TYPE_NAME    = 'service';
    const TAXONOMY_NAME     = TST_PREFIX.'service_category';

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
            'supports'              => ['title','editor'],
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
    

    public static function getSnippet($atts) {
        $atts           = array_change_key_case((array) $atts,CASE_LOWER);
        $shortcode_atts = shortcode_atts([
            'id'    => NULL,
            'full'  => 'false',
        ],$atts);
        if(!isset($shortcode_atts['id'])) return NULL;
        $shortcode_atts['full']   = filter_var($shortcode_atts['full'],FILTER_VALIDATE_BOOLEAN);
        $snippet        = get_post($shortcode_atts['id']);
        if($snippet){
            ob_start();?>
                <?php if($shortcode_atts['full'] === TRUE ): ?>
                <div id="snippet-post-<?= $snippet->ID ?>">
                    <div class="snippet-title"><?= $snippet->post_title; ?></div>
                    <div class="snippet-content"><?= $snippet->post_content; ?></div>
                </div>
                <?php else: ?>
                <?= $snippet->post_content ?>
            <?php endif;
            return ob_get_clean();
        }
    }

    public function __construct() {
        add_action('init','\TST\ServicePostType::RegisterTaxonomy');
        add_action('init','\TST\ServicePostType::RegisterPostType');
        add_shortcode('tst_snippet','\TST\ServicePostType::getSnippet');
    }
}
new ServicePostType;
}