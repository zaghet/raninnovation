<?php
namespace TST;
if( ! class_exists( '\TST\Asset' ) ) {
class Asset extends Main {
    
    public function __construct(){
		
		// add assets bundled by vite
		add_action( 'wp_enqueue_scripts', [$this,'add_vite_assets'], 100 );

		add_action( 'wp_head', [$this,'vite_client_head_hook'] );

		add_filter( 'script_loader_tag', [$this,'add_module_type_attribute'], 10, 3 );
		add_filter( 'style_loader_tag', [$this,'add_module_type_attribute'], 10, 3 );

		add_action('wp_enqueue_scripts', [$this,'cleaning_wordpress'], 100);

	}
	
	public function add_vite_assets() {
		global $wp_query;

		// add your custom js files here
		$js_files = [
			'main' => 'main.js'
		];

		// add your custom scss files here
		$scss_files = [
			'main' => 'main.scss'
		];

		if ( VITE_BUILD ) {
			$manifest = json_decode( file_get_contents( TST_DIST_PATH . '/.vite/manifest.json' ), true );
		}

		$theme_version      = wp_get_theme(get_template())->get('Version');

		foreach ( $js_files as $handle => $file ) {
			$js_uri = VITE_SERVER . '/assets/src/js/' . $file;
			if ( VITE_BUILD ) {
				$js_uri = TST_DIST_URI . '/' . $manifest[ 'assets/src/js/' . $file ]['file'];
			}
			
			
			wp_register_script( $handle, $js_uri, null, $theme_version, true );
			$vars = array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),// class_exists('\TST\Template') ? \TST\Template::theme_url( 'xhr.php' ) :
				'query_vars' => json_encode($wp_query->query_vars),
				'current_page' => get_query_var('paged') ? get_query_var('paged') : 1,
				'max_page' => $wp_query->max_num_pages,
				'template_directory' => get_template_directory(),
				'template_uri' => get_template_directory_uri(),
				'template_path' => parse_url(get_template_directory_uri(), PHP_URL_PATH),
				'template'     => get_template(),
				'theme_root'   => get_theme_root( get_template() ),
				'home_full_vh' => get_field('home_viewport_height_full', 'options'),
				'locomotive_scroll' => get_field('locomotive_scroll', 'options')
				
			);
			wp_localize_script( $handle, 'siteVars', $vars );
			wp_enqueue_script( $handle );
		}

		// load css main
		foreach ( $scss_files as $handle => $file ) {
			$css_uri = VITE_SERVER . '/assets/src/scss/' . $file;
			if ( VITE_BUILD ) {
				$css_uri = TST_DIST_URI . '/' . $manifest[ 'assets/src/scss/' . $file ]['file'];
			}
			
			wp_enqueue_style( $handle, $css_uri, null, $theme_version );
		}
	}
					
	function vite_client_head_hook() {
		if ( ! VITE_BUILD ) {
			echo '<script type="module" crossorigin src="' . VITE_SERVER . '/@vite/client"></script>';
		}
	}
	
	function add_module_type_attribute( $tag, $handle, $src ) {
		// The handles of the enqueued scripts we want to modify
		if ( 'main' === $handle && ! VITE_BUILD ) {
			return '<script type="module" src="' . esc_url( $src ) . '" crossorigin></script>';
		}
	
		return $tag;
	}
		
	function cleaning_wordpress() {
		// force all scripts to load in footer
		remove_action('wp_head', 'wp_print_scripts');
		remove_action('wp_head', 'wp_print_head_scripts', 9);
		remove_action('wp_head', 'wp_enqueue_scripts', 1);
	
		// removing all WP css files enqueued by default
		wp_dequeue_style('wp-block-library');
		wp_dequeue_style('wp-block-library-theme');
		wp_dequeue_style('wc-block-style');
		wp_dequeue_style('global-styles');
		wp_dequeue_style('classic-theme-styles');
	}
	

}
	new Asset();
}