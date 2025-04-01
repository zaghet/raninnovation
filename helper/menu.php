<?php
namespace TST;
if( ! class_exists( '\TST\Menu' ) ) {
class Menu {

    public function __construct(){
    
        add_action( 'init', [$this,'register_menu'] );
    }

    function register_menu() {
        register_nav_menus(
            array(
                'menu-main' => __( 'Menu principale' ),
                'menu-footer' => __( 'Menu footer' ),
            )
        );
    }

    function get_nav_menu_items($menu_name, $args = array()){

        $locations = get_nav_menu_locations();
        $menu = wp_get_nav_menu_object($locations[$menu_name]);
        return wp_get_nav_menu_items( $menu->term_id, $args);
    }

    function count_menu_item_children($menu_name, $item, $args = array()){

        $count      = 0;

        foreach($this->get_nav_menu_items($menu_name, $args) as $child_item){
            if($child_item->menu_item_parent == $item->ID){
                $count++;
            }
        }

        return $count;
    }

    function is_active_nav_menu_item($item){

        $current_url    = NET_HELPER::current_url();
        
        if(get_queried_object_id() == $item->object_id){
            echo "tst-active";

            return;
        }

        if(strpos($current_url, $item->url) !== false){
            echo "tst-active";

            return;
        }
        
    }
    
}
if( ! function_exists( 'TST_MENU_HELPER' ) ){
    function TST_MENU_HELPER(){
        return new Menu();
    }
}
TST_MENU_HELPER();
}