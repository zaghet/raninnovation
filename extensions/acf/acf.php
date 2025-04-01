<?php
namespace TST;
if( ! class_exists( '\TST\ACF' ) ) {
class ACF extends Main {

    public function __construct(){
        
        // imposta categoria blocchi
        add_filter( 'block_categories_all', [$this, 'register_category_blocks'] , 10, 2 );
        
        // salva json campi extra base
        add_filter( 'acf/settings/save_json', function ($path) {
			$path = __DIR__ . '/json';

			return $path;
		} );

        // load json campi extra base
		add_filter( 'acf/settings/load_json', function ($paths) {
			$paths = [
				__DIR__ . '/json'
			];

			return $paths;
		} );

        // nome file json campi extra base
		add_filter( 'acf/json/save_file_name', function ($filename, $post, $load_path){
			$filename = str_replace(
				array(
					' ',
					'_',
				),
				array(
					'-',
					'-'
				),
				$post['title']
			);
		
			$filename = strtolower( $filename ) . '.json';
		
			return $filename;
		} , 10, 3 );

        // registra le opzioni del tema
        $this->register_options();
        
        // registra i blocks
        $this->register_blocks();

		// carica i campi blocks di default
		$this->fields_blocks();

    }

    function register_category_blocks($categories, $post) {
        return array_merge(
            $categories,
            [
                [
                    'slug'  => 'tst-blocks',
                    'title' => esc_html__('Blocchi Twow theme', 'tst'),
                ],
            ]
        );
    }

    function register_blocks(){
		self::componentLoader('blocks', 0, __DIR__);
    }

	public static function register_block($block_name, $args = array()){

		$default_args = array(
			'name'              => "acf/{$block_name}",
			'title'             => ucfirst($block_name), // Nome del blocco con la prima lettera maiuscola
			'description'       => __("A {$block_name} block."),
			'render_template'   => "templates/blocks/{$block_name}/{$block_name}.php",
			'category'          => 'tst-blocks',
			'align'				=> 'full',
			'icon'              => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
										<path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
										<path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8m0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0M4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0"/>
									</svg>',
			'keywords'          => array($block_name),
			'enqueue_assets'    => function() use ($block_name, $args) {
				$theme_version = wp_get_theme()->get('Version');
				$manifest_path = TST_DIST_PATH . '/.vite/manifest.json';

				if (!file_exists($manifest_path)) {
					return;
				}

				$manifest = json_decode(file_get_contents($manifest_path), true);

				if (!isset($manifest["templates/blocks/{$block_name}/{$block_name}.js"])) {
					return;
				}

				$block = $manifest["templates/blocks/{$block_name}/{$block_name}.js"];
				if (isset($block['css'])) {
					foreach ($block['css'] as $css) {
						wp_enqueue_style("{$block_name}-block", TST_DIST_URI . '/' . $css, null, $theme_version);
					}
				}

				if (isset($block['file'])) {
					wp_enqueue_script($block["name"]."-block", TST_DIST_URI . '/' . $block['file'], array('jquery'), $theme_version, true);
				}

				// Enqueue extra styles
				if (isset($args['extra_style']) && !empty($args['extra_style'])) {
					foreach ($args['extra_style'] as $handle => $url) {
						wp_enqueue_style($handle, $url, array(), null);
					}
				}

				// Enqueue extra scripts
				if (isset($args['extra_script']) && !empty($args['extra_script'])) {
					foreach ($args['extra_script'] as $handle => $url) {
						wp_enqueue_script($handle, $url, array(), null, true);
					}
				}
			},
			'supports'          => array(
				'anchor'       => true,
				'align'        => true,
				'align_text'   => true,
				'align_content'=> true,
			),
		);

		$args = wp_parse_args($args, $default_args);
		if (function_exists('acf_register_block_type')) {
			acf_register_block_type($args);
		}

	}

    function register_options(){
        if (function_exists('acf_add_options_page')) {
            // pagina principale
			acf_add_options_page(array(
				'page_title'    => 'TST Theme Settings',
				'menu_title'    => 'Theme Settings',
				'menu_slug'     => 'tst-theme-settings',
				'capability' 	=> 'manage_options',
				'redirect'      => false
			));

            $tst_active_languages = [];

			if ( function_exists( 'pll_languages_list' ) ) {
				$tst_active_languages = pll_languages_list(
					[
						'hide_empty' => true,
						'fields' => 'slug'
					]
				);
			}

            // pagine per lingua
			if ( $tst_active_languages ) {
				foreach ( $tst_active_languages as $lang ) {
					acf_add_options_sub_page([
						'page_title' => "Theme " . $lang,
						'menu_title' => __("Theme ", 'tst') . $lang,
						'menu_slug' => "tst-theme-lang-" . $lang,
						'parent_slug'   => 'tst-theme-settings',
						'capability' => 'manage_options',
						'post_id' => 'option-' . $lang,
					]);
				}
			}
			
		}
    }

    // carica i group fields dei blocchi base
    function fields_blocks(){
		self::componentLoader('fields', 0, __DIR__);  
    }
}
}

if (class_exists('\TST\ACF') && class_exists('ACF')) {
	add_action('after_setup_theme', function(){
		new ACF();	
	});	
}else{
	add_action( 'admin_notices', function(){
		?>
		<div class="notice notice-warning is-dismissible">
		<p><?php _e( 'Installare Plugin ACF' ); ?></p>
		</div>
		<?php
	} );
}