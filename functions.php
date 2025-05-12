<?php

define('¶',PHP_EOL);
define('TST_NAME','TWOW Default (Classic) Theme');
define('TST_TITLE','TWOW Starter Theme');
define('TST_VERSION','1.0.0');
define('TST_PREFIX','tst_');
// Define dist directory, base uri, and path
define( 'TST_DIST_DIR', 'assets/dist' );
define( 'TST_DIST_URI', get_template_directory_uri() . '/' . TST_DIST_DIR );
define( 'TST_DIST_PATH', get_template_directory() . '/' . TST_DIST_DIR );
// Define theme directory
define( 'TST_PARTIAL_DIR', 'partials' );
define( 'TST_PARTIAL_PATH', get_template_directory() . '/' . TST_PARTIAL_DIR );
define( 'TST_TPL_DIR', 'templates' );
define( 'TST_TPL_PATH', get_template_directory() . '/' . TST_TPL_DIR );

// default server address, port, and entry point can be customized in vite.config.js
define( 'VITE_SERVER', 'http://localhost:5173' );
define( 'VITE_BUILD', file_exists( TST_DIST_PATH . '/.vite/manifest.json' ) );

// config
define( 'TST_CONFIG', [
    'landing' =>  false,
    'blog' => [
        'filter' => false,
        'leading' => 0,
    ],
    'layout' => [
        'sidebar' => 'left',
    ],
    'header' => [
        'sticky' => false,
        'mobile' => true,
    ],
    'footer' => [
        'sticky' => false,
    ],
    'search' => [
        'results' => 10,
    ],
    'pagination' => [
        'posts_per_page' => 9,
        'posts_per_archive' => 12,
        'posts_per_home' => 9,
    ],
]);

require_once('library/aq_resizer.php');

include( 'core/main.php' );

new \TST\Main(['start' => __DIR__]);

// // Sostituzione Marker WP Store Locator
// add_filter( 'wpsl_admin_marker_dir', 'custom_admin_marker_dir' );

// function custom_admin_marker_dir() {

//     $admin_marker_dir = get_stylesheet_directory() . '/wpsl-markers/';
//     define( 'WPSL_MARKER_URI', get_stylesheet_directory_uri() . '/wpsl-markers/' );
//     error_log( 'Percorso marker: ' . $admin_marker_dir );
//     return $admin_marker_dir;
// }

// Definire prima del caricamento del plugin
if ( ! defined( 'WPSL_MARKER_URI' ) ) {
    define( 'WPSL_MARKER_URI', get_stylesheet_directory_uri() . '/wpsl-markers/' );
}

// Sostituzione marker lato backend (admin)
add_filter( 'wpsl_admin_marker_dir', 'custom_admin_marker_dir' );

function custom_admin_marker_dir() {
    return get_stylesheet_directory() . '/wpsl-markers/';
}
