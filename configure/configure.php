<?php
namespace TST;
if( ! class_exists( '\TST\Configure' ) ) {
class Configure extends Main {
    
    public function __construct(){
        add_action('after_setup_theme', [$this, 'custom_setup']);

        // disabling big image sizes scaled
        add_filter( 'big_image_size_threshold', '__return_false' );

        // Giving credits
        add_filter('admin_footer_text', [$this, 'remove_footer_admin']);

        // Remove WP Emoji
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action('wp_head', 'wp_generator');
        add_filter('the_generator', '__return_empty_string');

        // delete jquery migrate
        add_filter( 'wp_default_scripts', [$this, 'dequeue_jquery_migrate'] );

        //disable update emails
        add_filter( 'auto_plugin_update_send_email', '__return_false' );
        add_filter( 'auto_theme_update_send_email', '__return_false' );

        //Disable shortlink.
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action ('template_redirect', 'wp_shortlink_header', 11, 0);

        // Disable Windows Live Writer manifest.
        remove_action('wp_head', 'wlwmanifest_link');

        // Disable XML RPC.
        
        add_filter('xmlrpc_enabled', '__return_false');
        add_filter('wp_headers', [$this, 'remove_x_pingback']);
        add_filter('pings_open', '__return_false', 9999);
        remove_action('wp_head', 'rsd_link');
    }

    public function remove_x_pingback($headers) {
        unset($headers['X-Pingback'], $headers['x-pingback']);
        return $headers;
    }

    public function dequeue_jquery_migrate( &$scripts){
        if(!is_admin()){
            $scripts->remove( 'jquery');
            $scripts->add('jquery', 'https://code.jquery.com/jquery-3.6.1.min.js', null, null, true );
        }
    }

    public function remove_footer_admin () {
        echo 'Template creato per <a href="http://www.twow.it" target="_blank">TWOW</a>';
    }

    public function custom_setup() {
        show_admin_bar(false);

        // Auto update disabled by default
        add_filter('auto_update_plugin', '__return_false');
        add_filter('auto_update_theme', '__return_false');
        add_filter('auto_update_core', '__return_false');
        add_filter('auto_update_translation', '__return_false');

        // Add support for editor styles.
        add_theme_support( 'editor-styles' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'align-wide' );
        add_theme_support( 'post-formats', array( 'link', 'image', 'gallery' ) );

        /**
         * Add support for appearance tools.
         *
         * @link https://wordpress.org/documentation/wordpress-version/version-6-5/#add-appearance-tools-to-classic-themes
         */
        add_theme_support( 'appearance-tools' );

        // Images
        add_theme_support( 'post-thumbnails' );

        // Languages
        load_theme_textdomain('tst', get_template_directory() . '/languages');

        // HTML 5 - Example : deletes type="*" in scripts and style tags
        add_theme_support( 'html5', [ 'search-form',
                            'comment-form',
                            'comment-list',
                            'gallery',
                            'caption',
                            'widgets','script', 'style' ] );
        

        // Remove SVG and global styles
        remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
        remove_action('wp_body_open', 'wp_global_styles_render_svg_filters' );

        // Remove wp_footer actions which add's global inline styles
        remove_action('wp_footer', 'wp_enqueue_global_styles', 1);

        // Remove render_block filters which adds unnecessary stuff
        remove_filter('render_block', 'wp_render_duotone_support');
        remove_filter('render_block', 'wp_restore_group_inner_container');
        remove_filter('render_block', 'wp_render_layout_support_flag');

    }

}
    new Configure();
}