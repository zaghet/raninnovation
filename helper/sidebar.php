<?php
namespace TST;
if( ! class_exists( '\TST\Sidebar' ) ) {
class Sidebar {

    public function __construct(){
        // add_action( 'widgets_init', [$this, 'minicart'] );
    }

    function minicart(){
        register_sidebar(
            array(
                'name'          => 'Mini-cart Sidebar',
                'id'            => 'mini-cart-sidebar',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4>',
                'after_title'   => '</h4>',
            )
        );
    }
    
}
if( ! function_exists( 'TST_SIDEBAR_HELPER' ) ){
    function TST_SIDEBAR_HELPER(){
        return new Sidebar();
    }
}
TST_SIDEBAR_HELPER();
}