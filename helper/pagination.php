<?php
namespace TST;
if( ! class_exists( '\TST\Pagination' ) ) {
class Pagination {

    public function __construct() {
        add_action( 'pre_get_posts', '\TST\Pagination::setPostPerPage' );
        // add_filter( 'paginate_links_output', [$this, 'links_output'], 99, 2);
	}

    public static function setPostPerPage(){
        global $wp_query;
        if (!is_admin() && $wp_query->is_main_query()){
            if(is_home()){
                $wp_query->set('posts_per_page', TST_CONFIG['pagination']['posts_per_home']);
            }
            if(is_category() || is_archive()){
                $wp_query->set('posts_per_page', TST_CONFIG['pagination']['posts_per_archive']);
            }
        }
    }

    public static function print( $config = array() ) {
        $html = self::get( $config );

        if ( $html ) {
            $theme_key = 'tst';

            ob_start();
            posts_nav_link( ' &#183; ', 'previous page', 'next page' );
            ob_end_clean();

            printf( '<div class="%s-page-navigation">', esc_attr( $theme_key ) );
                echo wp_kses_post( $html );
            echo '</div>';
        }
    }

    public static function get( $config = array() ) {
        if ( false === $config ) {
            return '';
        }

        global $wp_query;

        $config = wp_parse_args(
            $config,
            array(
                'query' => $wp_query,
                'title' => __( 'Posts navigation', 'tst' ),
                'range' => 1,
                'prev'  => __( '&larr;', 'tst' ),
                'next'  => __( '&rarr;', 'tst' ),
            )
        );
        // var_dump($config);

        $query = $config['query'];
        $title = $config['title'];
        $range = $config['range'];
        $prev  = $config['prev'];
        $next  = $config['next'];

        $allowed_html = array(
            'span' => array(
                'class' => array(),
            ),
        );

        /* Total number of pages. */
        $pages = $query->max_num_pages ? absint( $query->max_num_pages ) : 1;

        if ( $pages <= 1 ) {
            return '';
        }

        $html = '';

        /* Current page. */
        $paged = 1;

        if ( get_query_var( 'paged' ) ) {
            $paged = absint( get_query_var( 'paged' ) );
        } elseif ( get_query_var( 'page' ) ) {
            $paged = absint( get_query_var( 'page' ) );
        }

        /* Link back to the first page. */
        $show_first = true;

        /* Link to the last page. */
        $show_last = true;

        /* Link to the next page. */
        $show_next = $paged < $pages;

        /* Link to the previous page. */
        $show_prev = $paged > 1;

        $html .= '<nav aria-label="Blog navigation">';
        // if ( ! empty( $config['title'] ) ) {
        // 	$html .= sprintf( '<h2 class="screen-reader-text">%s</h2>', esc_html( $config['title'] ) );
        // }

        $link    = '<li class="page-item"><a class="%s page-link" title="%s" href="%s">%s</a></li>';
        $current = '<li class="page-item active"><span class="%s page-link" title="%s" href="%s">%s</span></li>';

        $html .= '<ul class="pagination justify-content-center">';
        
        //prev page
        if ( $show_prev ) {
            $html .= sprintf(
                '<li class="page-item"><a class="prev page-link" title="%s" href="%s">%s</a></li>',
                esc_attr( __( 'Vai alla pagina precedente', 'tst' ) ),
                esc_attr( get_pagenum_link( $paged - 1 ) ),
                esc_html( $prev )
            );
        }

        if ( $show_first ) {
            $show_first_class = '';
            $show_first_html  = $link;

            if ( 1 === $paged ) {
                $show_first_class = 'current';
                $show_first_html  = $current;
            }

            $html .= sprintf(
                $show_first_html,
                esc_attr( $show_first_class ),
                esc_attr( trad___( 'Vai alla prima pagina') ),
                esc_attr( get_pagenum_link( 1 ) ),
                esc_html( 1 )
            );
        }

        if ( $paged - $range > 2 ) {
            $html .= '<li class="page-item"><span class="page-numbers dots">&hellip;</span></li>';
        }

        for ( $i = 2; $i < $pages; $i++ ) {
            if ( $i <= $paged + $range && $i >= $paged - $range ) {
                $number_class = '';
                $number_html  = $link;

                if ( $i === $paged ) {
                    $number_class = 'current';
                    $number_html  = $current;
                }

                /* translators: page number */
                $text = sprintf( wp_kses( trad___( '<span class="meta-nav screen-reader-text">Pagina </span>%s' ), $allowed_html ), $i );

                $html .= sprintf(
                    $number_html,
                    esc_attr( $number_class ),
                    /* translators: page number */
                    esc_attr( sprintf( trad___( 'Vai alla pagina %s'), $i ) ),
                    esc_attr( get_pagenum_link( $i ) ),
                    wp_kses_post( $text ),
                    esc_html( $i )
                );
            }
        }

        if ( $paged < $pages - $range - 1 ) {
            $html .= '<li class="page-item"><span class="page-numbers dots">&hellip;</span></li>';
        }

        if ( $show_last ) {
            $show_last_class = '';
            $show_last_html  = $link;

            if ( $pages === $paged ) {
                $show_last_class = 'current';
                $show_last_html  = $current;
            }

            $html .= sprintf(
                $show_last_html,
                esc_attr( $show_last_class ),
                esc_attr( trad___( 'Vai a ultima pagina' ) ),
                esc_attr( get_pagenum_link( $pages ) ),
                esc_html( $pages )
            );
        }

        // next page
        if ( $show_next ) {
            $html .= sprintf(
                '<li class="page-item"><a class="next page-link" title="%s" href="%s">%s</a></li>',
                esc_attr( trad___( 'Vai alla prossima pagina') ),
                esc_attr( get_pagenum_link( $paged + 1 ) ),
                esc_html( $next )
            );
        }

        
            $html .= '</ul>';
        $html     .= '</nav>';

        return $html;
    }

    public static function TaxPaginationQuery($posts_per_page = 9, $cat = null){
        global $wp_query;
        $paged  = (get_query_var('paged')) ? absint(get_query_var('paged' )) : 1;
        $extra_args['paged'] = $paged;
        $extra_args['posts_per_page'] = $posts_per_page;
        if($cat)
            $extra_args['cat'] = $cat;
        $args   = array_merge($wp_query->query_vars, $extra_args);
        return new \WP_Query($args);
    }

    protected static function getTaxPagination($custom_query=NULL,$args=NULL){
        /*  */
        if(!isset($custom_query)) return FALSE;
        $args   = isset($args) ? $args : [];
        $total_pages    = $custom_query->max_num_pages;
        var_dump( get_query_var('paged') );
        if($total_pages <= 1) return FALSE;
        $big            = 999999999;
        $defaults = [
            'base'               => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'             => '?paged=%#%',
            'total'              => $total_pages,
            'current'            => max(1, get_query_var('paged')),
            'aria_current'       => 'page',
            'show_all'           => FALSE,
            'prev_next'          => TRUE,
            'prev_text'          => trad___('Prec'),
            'next_text'          => trad___('Succ'),
            'end_size'           => 1,
            'mid_size'           => 2,
            'type'               => 'list',
            'add_args'           => [],
            'add_fragment'       => '',
            'before_page_number' => '',
            'after_page_number'  => '',
        ];
        $args   = wp_parse_args($args,$defaults);
       
        return paginate_links( $args );
    }

    public static function printTaxPagination($paged_query=NULL,$args=NULL){
        if(!isset($paged_query)) return; ?>
<nav class="d-flex justify-content-center tst-pagination py-5">
    <?= self::getTaxPagination($paged_query,$args); ?>
</nav>
<?php wp_reset_postdata();
    }

    public function links_output( $html, $args){
        $r = str_replace( "<ul class='page-numbers'>", "<ul class='pagination justify-content-center'>", $html);
        $r = str_replace( '<a class="', '<a class="page-link ', $r);
        $r = str_replace( 'class="page-numbers', 'class="page-link page-numbers', $r);
        return $r;
    }

}
if( ! function_exists( 'TST_PAGINATION_HELPER' ) ){
    function TST_PAGINATION_HELPER(){
        return new Pagination();
    }
}
TST_PAGINATION_HELPER();
}