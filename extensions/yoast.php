<?php
namespace TST;
if( ! class_exists( '\TST\YOAST' ) ) {
class YOAST {
    
    public function __construct(){
		// stampo il breadcrumb
		add_action( 'tst_before_content', [$this, 'print_breadcrumb'] );

        // filtri modifica breadcrumb
		add_filter( 'wpseo_breadcrumb_links', [$this, 'custom_breadcrumb'] );
		
		add_filter( 'wpseo_breadcrumb_output_wrapper', [$this, 'output_wrapper'] );

		add_filter( 'wpseo_breadcrumb_single_link_wrapper', [$this, 'link_wrapper'] );
				
		add_filter('wpseo_sitemap_urlimages', [$this, 'filter_wpseo_sitemap_urlimages'], 10, 2);

		add_filter('wpseo_sitemap_urlimages_term', [$this, 'filter_wpseo_sitemap_urlimages_term'], 10, 2);
	
	
		// nasconde pagine in lingua dalla sitemap
		add_filter( 'wpseo_sitemap_entry', [$this,'exclude_lang_page_from_sitemap'], 999, 3 );
	}

	public function exclude_lang_page_from_sitemap($url, $type, $post){
		if( !get_field( 'enable_languages', 'option') && function_exists('pll_get_post_language')){
			if ( pll_get_post_language($post->ID) !== 'it' ) {
				return '';
			}
		}
		return $url;
	}

	function print_breadcrumb() {
      if (function_exists('yoast_breadcrumb') && !is_front_page()) {
        yoast_breadcrumb('<section id="breadcrumb"><div class="container container-breadcrumb"><div class="row"><nav aria-label="breadcrumb">', '</nav></div></div></section>');
      }
    }

    function custom_breadcrumb( $crumbs ) {
		$obj = get_queried_object();
		// rimuovo Shop automatico inserito da WC
		// foreach ($crumbs as $key => $crumb) {
		// 	if ( $crumb["text"] == "Shop" )
		// 		unset($crumbs[$key]);
		// }

		// aggiunta prodotti come hom shop in single prodotti e archive taxonomy
		// if( is_singular( 'product' ) || is_product_category() || is_product_taxonomy() ){
		// 	$breadcrumb[] = array(
		// 		'url' => get_permalink(wc_get_page_id( 'shop' )),
		// 		'text' => __('Prodotti', 'tst'),
		// 	);
		// 	array_splice( $crumbs, 1, 0, $breadcrumb );
		// }

		return $crumbs;
	}

	function output_wrapper() {
		return 'ol';
	}

	function link_wrapper() {
		return 'li';
	}

	// Recupero immagini dei Post/Custom Post
	function filter_wpseo_sitemap_urlimages( $images, $post_id ) {
		// Recupero il contenuto del post
		$post_content = get_post_field('post_content', $post_id);
		// Cerco tutte le immagini nel contenuto del post con un'espressione regolare
		preg_match_all('/<img.*?src=["\'](.*?)["\']/', $post_content, $matches);
		// Se ci sono immagini nel contenuto, aggiungo alla sitemap
		if (!empty($matches[1])) {
			foreach ($matches[1] as $image_url) {
				$images[] = [
					'src'   => $image_url,
					'title' => get_the_title($post_id)
				];
			}
		}
		// Recupero tutte le immagini allegate al post (dalla libreria media)
		$args = [
			'post_parent'    => $post_id,
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'posts_per_page' => -1
		];
		$attachments = get_posts($args);
		// Se ci sono immagini allegate, aggiungo alla sitemap
		foreach ($attachments as $attachment) {
			$image_url = wp_get_attachment_url($attachment->ID);
			if ($image_url) {
				$images[] = [
					'src'   => $image_url,
					'title' => get_the_title($attachment->ID)
				];
			}
		}

		return $images;
	}

	function filter_wpseo_sitemap_urlimages_term( $images, $term_id ) {
		// Recupero la tassonomia del termine
		$taxonomy = get_term($term_id)->taxonomy;

		// 1. Immagini per le categorie del blog
		if ($taxonomy === 'category') {
			// Recupero l'immagine associata alla categoria del blog tramite ACF
			$category_image = get_field('img_category_blog', 'category_' . $term_id);

			// Se l'immagine esiste, aggiungo alla sitemap
			if ($category_image && isset($category_image['url'])) {
				$images[] = [
					'src'   => $category_image['url'],
					'title' => get_term($term_id)->name
				];
			}
		}

		// 2. Immagini per le categorie dei prodotti
		if ($taxonomy === 'categoria') {
			// Recupero l'immagine associata alla categoria dei prodotti tramite ACF
			$category_image = get_field('category_card_image', 'category_' . $term_id);

			// Se l'immagine esiste, aggiungo alla sitemap
			if ($category_image && isset($category_image['url'])) {
				$images[] = [
					'src'   => $category_image['url'],
					'title' => get_term($term_id)->name
				];
			}
		}

		return $images;
	}

}
}
if (class_exists( 'WPSEO_Options') && class_exists( '\TST\YOAST' )) {
	add_action('after_setup_theme', function(){
		new YOAST();	
	});
}else {
	add_action( 'admin_notices', function(){
		?>
		<div class="notice notice-warning is-dismissible">
		<p><?php _e( 'Installare Plugin YOAST' ); ?></p>
		</div>
		<?php
	} );
}