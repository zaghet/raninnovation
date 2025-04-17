<?php
namespace TST;
if( ! class_exists( '\TST\Blog' ) ) {
    class Blog extends Main {
        private $last_post_id;
        public function __construct() {
            // Stampa i blocchi sfruttando hook tst_page_content_before
            add_action( 'tst_page_content_before', [$this, 'blog_content'], 2 );
        }
        public function blog_content() {
            if (is_home()) { // Controllo che la pagina sia il Blog
                $post_id = get_option('page_for_posts'); // Recupera l'ID della pagina assegnata al blog
                if ($post_id) {
                    $post = get_post($post_id);
                    if ($post) {
                        echo apply_filters('the_content', $post->post_content); // Stampa i blocchi
                    }
                }
            }
        }
    }
}
$tst_blog_helper = new Blog();