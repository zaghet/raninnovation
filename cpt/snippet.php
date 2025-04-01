<?php 
namespace TST;
if(!defined('ABSPATH') ) {
    exit; // Exit if accessed directly
}

if( ! class_exists( '\TST\SnippetPostType' ) ) {
class SnippetPostType extends Main {
    const POST_TYPE_NAME    = 'snippet';
    const TAXONOMY_NAME     = TST_PREFIX.'snippet_category';

    public static function RegisterTaxonomy(){
        $args = [
            'description'           => __('Categorie di ', 'tst' ) . self::POST_TYPE_NAME,
            'public'                => FALSE,
            'publicly_queryable'    => FALSE,
            'hierarchical'          => FALSE,
            'show_ui'               => TRUE,
            'show_in_menu'          => TRUE,
            'show_in_nav_menus '    => FALSE,
            //'show_in_rest'          => FALSE,
            //'rest_base'             => 'STRING',
            //'rest_namespace'        => 'STRING',
            //'rest_controller_class' => 'STRING',
            'show_tagcloud'         => FALSE,
            'show_in_quick_edit'    => TRUE,
            'show_admin_column'     => true,
            'meta_box_cb'           => 'post_categories_meta_box',
            //'meta_box_sanitize_cb'    => 'CALLABLE',
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => FALSE,
            /*'default_term'          => [
                'name'                  => __('Thank You Messages','tst'),
                'slug'                  => 'thank-you',
                'description'           => __('Positively responding to a user request','tst'),
                ],*/
            'labels'                => [
                'name'                          => _x('Categorie','taxonomy general name','tst'),
                'singular_name'                 => _x('Categoria','taxonomy singular name','tst'),
                'search_items'                  => __('Cerca categoria','tst'),
                'popular_items'                 => __('Categorie popolari','tst'),
                'all_items'                     => __('Tutte le categorie','tst'),
                'parent_item'                   => NULL,
                'parent_item_colon'             => NULL,
                'edit_item'                     => __('Edit categoria','tst'),
                'update_item'                   => __('Update categoria','tst'),
                'add_new_item'                  => __('Add New categoria','tst'),
                'new_item_name'                 => __('New categoria','tst'),
                'separate_items_with_commas'    => __('Separate categoria with commas','tst'),
                'add_or_remove_items'           => __('Add or remove categorie','tst'),
                'choose_from_most_used'         => __('Choose from the most used categorie','tst'),
                'not_found'                     => __('No categorie found.','tst'),
                'menu_name'                     => __('Categorie','tst'),
            ],
        ];
        register_taxonomy(self::TAXONOMY_NAME,[self::POST_TYPE_NAME],$args);
    }

    public static function RegisterPostType(){
        $args = [
            'description'           => __('Snippets','tst'),
            'public'                => FALSE,
            'publicly_queryable'    => FALSE,
            'hierarchical'          => FALSE,
            'exclude_from_search'   => TRUE,
            'show_ui'               => TRUE,
            'show_in_menu'          => TRUE,
            'show_in_nav_menus '    => TRUE,
            'show_in_admin_bar'     => TRUE,
            //'show_in_rest'          => FALSE,
            //'rest_base'             => 'STRING',
            //'rest_namespace'        => 'STRING',
            //'rest_controller_class' => 'STRING',
            'menu_position'         => NULL,
            'menu_icon'             => 'dashicons-text',
            'capability_type'       => 'page',
            //'map_meta_cap'          => FALSE,
            'supports'              => ['title','editor'],
            //'register_meta_box_cb'  => 'CALLABLE',
            'taxonomies'            => [self::TAXONOMY_NAME],
            'has_archive'           => FALSE,
            'rewrite'               => FALSE,
            'query_var'             => TRUE,
            'can_export'            => TRUE,
            'delete_with_user'      => FALSE,
            //'template'              => [],
            //'template_lock'       => FALSE,
            'label'                 => NULL,
            'labels'                => [
                'name'                  => _x('Snippets','Post type general name','tst'),
                'singular_name'         => _x('Snippet','Post type singular name','tst'),
                'menu_name'             => _x('Snippets','Admin Menu text','tst'),
                'name_admin_bar'        => _x('Snippet','Add New on Toolbar','tst'),
                'add_new'               => __('Add New','tst'),
                'add_new_item'          => __('Add New Snippet','tst'),
                'new_item'              => __('New Snippet','tst'),
                'edit_item'             => __('Edit Snippet','tst'),
                'view_item'             => __('View Snippet','tst'),
                'all_items'             => __('All Snippets','tst'),
                'search_items'          => __('Search Snippets','tst'),
                'parent_item_colon'     => __('Parent Snippets:','tst'),
                'not_found'             => __('No snippets found.','tst'),
                'not_found_in_trash'    => __('No snippets found in Trash.','tst'),
                'featured_image'        => _x('Snippet Cover Image','Overrides the “Featured Image” phrase for this post type. Added in 4.3','tst'),
                'set_featured_image'    => _x('Set cover image','Overrides the “Set featured image” phrase for this post type. Added in 4.3','tst'),
                'remove_featured_image' => _x('Remove cover image','Overrides the “Remove featured image” phrase for this post type. Added in 4.3','tst'),
                'use_featured_image'    => _x('Use as cover image','Overrides the “Use as featured image” phrase for this post type. Added in 4.3','tst'),
                'archives'              => _x('Snippets archives','The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4','tst'),
                'insert_into_item'      => _x('Insert into snippet','Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4','tst'),
                'uploaded_to_this_item' => _x('Uploaded to this snippet','Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4','tst'),
                'filter_items_list'     => _x('Filter snippets list','Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4','tst'),
                'items_list_navigation' => _x('Snippets list navigation','Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4','tst'),
                'items_list'            => _x('Snippets list','Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4','tst'),
            ],
        ];
        register_post_type(self::POST_TYPE_NAME,$args);
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
        add_action('init','\TST\SnippetPostType::RegisterTaxonomy');
        add_action('init','\TST\SnippetPostType::RegisterPostType');
        add_shortcode('tst_snippet','\TST\SnippetPostType::getSnippet');
    }
}
new SnippetPostType;
}