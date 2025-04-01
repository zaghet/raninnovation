<?php
namespace TST;
if( ! class_exists( '\TST\WP' ) ) {
class WP {

    public function __construct() {

        /* Rimuovo prefisso "Categoria:", "Archivio:", ecc */
        add_filter( 'get_the_archive_title', [$this, 'get_the_archive_title']);
        
        // modifico enqueue js block
        add_filter( 'script_loader_tag', [$this, 'add_type_attribute'], 10, 3);
    
        // disable auto update
        add_filter( 'auto_update_theme', '__return_false' );	
        add_filter( 'auto_update_plugin', '__return_false' );

    }

    function get_static_dir() {
        return get_template_directory_uri() . '/static';
    }

    public function theme_version(){
        return wp_get_theme()->get('Version');
    }

    public function manifest(){
        return TST_DIST_PATH . '/.vite/manifest.json';
    }

    public function add_type_attribute($tag, $handle, $src) {

        if ( strpos($handle, 'block/') === false && $handle !== 'main' ) {
            return $tag;
        }
        $handle = end(explode( '/', $handle));
        $tag = '<script id="'.$handle.'-js" type="module" src="' . esc_url( $src ) . '"></script>';
        return $tag;
    }

    public function get_the_archive_title() {
   
            if ( is_category() ) {    
                    $title = single_cat_title( '', false );    
                } elseif ( is_tag() ) {    
                    $title = single_tag_title( '', false );    
                } elseif ( is_author() ) {    
                    $title = '<span class="vcard">' . get_the_author() . '</span>' ;    
                } elseif ( is_tax() ) { //for custom post types
                    $title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
                } elseif (is_post_type_archive()) {
                    $title = post_type_archive_title( '', false );
                }
            return $title;    
        
    }


    public function get_title(){
        
        if(is_archive()){
            return get_the_archive_title();
        }else if(is_404()){
            return "Pagina non trovata";
        }else if(is_search()){
            return "Ricerca";
        }else{
            return get_the_title();
        }

    }

    public function get_description(){
        
        if(is_tax()){
            return term_description();
        }else if(is_archive()){
            return get_the_archive_description();
        }else{
            if(!empty(get_field("sottotitolo_pagina"))){
                return get_field("sottotitolo_pagina");
            }

            return "";
            //return get_the_excerpt();
        }

    }

    function get_entry_date( $classes = '', $post_ID = '' ) {
        if ( ! $post_ID ) {
            $post_ID = get_the_ID();
        }

        $time_string = '<time class="entry-date published updated ' . esc_attr( $classes ) . '" datetime="%1$s">%2$s</time>';

        if ( get_the_time( 'U', $post_ID ) !== get_the_modified_time( 'U', $post_ID ) ) {
            $time_string = '<time class="entry-date published ' . esc_attr( $classes ) . '" datetime="%1$s">%2$s</time><time class="updated screen-reader-text ' . esc_attr( $classes ) . '" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr( get_the_date( 'c', $post_ID ) ),
            get_the_date( '', $post_ID ),
            esc_attr( get_the_modified_date( 'c', $post_ID ) ),
            get_the_modified_date( '', $post_ID )
        );

        return $time_string;
    }

    public function entry_date( $classes = '', $post_ID = '' ) {
        echo $this->get_entry_date($classes, $post_ID ); 
    }

    public function term_list( $post_ID = '' ) 
    {
        if( $post_ID == '' ) {
            $post = get_post();
            $post_ID = $post->ID;
        }

        $separator = ' ';
        $output    = [];
        $class = 'btn btn-secondary btn-sm rounded-pill';

        $post_categories = get_the_category( $post_ID );
        if ( $post_categories ) {
            foreach( $post_categories as $post_category ) {

                $output[] = '<a class="' . $class . '" href="' . esc_url( get_category_link( $post_category ) ) . '" alt="' . esc_attr( sprintf( pll__( 'Vedi tutti i post in %s' ), $post_category->name ) ) . '"> 
                                    <span>' . esc_html( $post_category->name ) . '</span>
                            </a>';
            }

            if ( $output )
                echo implode( $separator, $output );
        }
    }

    public function get_post_categories( $post_ID = '' ) {
        if ( ! $post_ID ) {
            $post_ID = get_the_ID();
        }

        $categories_list = $this->term_list( $post_ID );


        if ( $categories_list ) {
            return $categories_list;
        }

        return '';
    }

    public function post_categories( $post_ID = '' ) {
        $categories = $this->get_post_categories( $post_ID );

        if ( $categories ) {
            printf(
                '<div class="mb-5">%s</div>',
                wp_kses_post( $categories )
            );
        }
    }
}
if( ! function_exists( 'TST_WP_HELPER' ) ){
    function TST_WP_HELPER(){
        return new WP();
    }
}
TST_WP_HELPER();
}