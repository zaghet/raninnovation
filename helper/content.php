<?php
namespace TST;
if( ! class_exists( '\TST\Content' ) ) {
class Content extends Main {

	const PARTIAL_LOOP_PREFIX    = 'card';

    public function __construct(){

	  	// wrapper main content
		add_action( 'tst_page_content_before', [$this, 'main_start'], 1 );
		add_action( 'tst_page_content_after', [$this, 'main_end'], 999 );
	  
		// content
		add_action( 'tst_page_content', [$this, 'content_output'], 1 );

		// partial loop per tipo di contenuto
		add_filter( 'tst_loop_content_partial', [$this, 'loop_content_partial'], 10, 2 );
        
		// after page
		add_action('tst_after_page', function(){
			echo '<div id="content-after-page"></div>';
		});
	}

	public function loop_content_partial($template, $post_type) {
		if( file_exists( TST_PARTIAL_PATH . '/' . self::PARTIAL_LOOP_PREFIX . '-' . $post_type . '.php' ) ){
			return $post_type;
		}
		return $template;
	}

	public static function page_content() {

		do_action( 'tst_page_content_before' );

		do_action( 'tst_page_content' );

		do_action( 'tst_page_content_after' );
	}

	public static function content_loop( $args = array() ) {
		global $wp_query;
			
		$query = $wp_query;
		
		$query = apply_filters( 'tst_loop_query', $query );
		
		$content_base = apply_filters( 'tst_loop_content_base_template', 'post' );

		$params = array(
			'query' => $query,
		);

		if ( $query->have_posts() ) {
			
			do_action( 'tst_loop_start' );

			while ( $query->have_posts() ) {
				$query->the_post();

				$loop_content_partial = apply_filters( 'tst_loop_content_partial', $content_base, get_post_type() );
				
				get_template_part( 'partials/' . self::PARTIAL_LOOP_PREFIX, $loop_content_partial );
			}

			do_action( 'tst_loop_end' );

		} else {
			get_template_part( 'templates/content', 'none' );
		}

		wp_reset_postdata();

		$pagination_config = isset( $args['pagination'] ) ? $args['pagination'] : array(
			'query' => $query,
		);

		$pagination_config = apply_filters( 'tst_loop_pagination_config', $pagination_config );

		Pagination::print( $pagination_config );
	}

	public function main_start() {
		echo '<main id="main" class="site-main">';
	}
	
	public function main_end() {
		echo '</main><!-- #main -->';
	}

	public function content_output() {
		
		if( is_front_page() ) {
			$template = 'templates/content-home';
		}elseif ( is_home() || is_archive() ){
			$template = 'templates/content-loop';
		}elseif ( is_search() ) {
			$template = 'templates/content-loop';
		} elseif ( is_404() ) {
			$template = 'templates/content-404';
		} elseif ( is_singular() && ! is_page() ) {
			$template = 'templates/content-post';
		} else {
			$template = 'templates/content-page';
		}

		get_template_part( apply_filters( 'tst_page_content_output', $template ) );
	}

}

$tst_content_helper = new Content();
}