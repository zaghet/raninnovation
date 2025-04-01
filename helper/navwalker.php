<?php
namespace TST;
use \Walker_Nav_menu;

// dropdown_fix Bootstrap 5.0.0
if( ! function_exists( '\TST\bootstrap5_dropdown_fix' ) ) {
add_filter('nav_menu_link_attributes', __NAMESPACE__ . '\bootstrap5_dropdown_fix');
function bootstrap5_dropdown_fix($atts){
	if (array_key_exists('data-toggle', $atts)) {
		unset($atts['data-toggle']);
		$atts['data-bs-toggle'] = 'dropdown';
	}
	return $atts;
}
}

if( ! class_exists( '\TST\Navwalker' ) ) {
class Navwalker extends Walker_Nav_menu {
  private $current_item;
  private $column_item;
  private $current_megamenu;
  private $menu_type = array();
  private $dropdown_menu_alignment_values = [
    'dropdown-menu-start',
    'dropdown-menu-end',
    'dropdown-menu-sm-start',
    'dropdown-menu-sm-end',
    'dropdown-menu-md-start',
    'dropdown-menu-md-end',
    'dropdown-menu-lg-start',
    'dropdown-menu-lg-end',
    'dropdown-menu-xl-start',
    'dropdown-menu-xl-end',
    'dropdown-menu-xxl-start',
    'dropdown-menu-xxl-end'
  ];

	function start_lvl(&$output, $depth = 0, $args = null) {

		if($depth==0){
			$this->current_megamenu = false;
			// $this->current_item->parent_hasmegamenu='';
		}

    	$dropdown_menu_class[] = '';
		foreach($this->current_item->classes as $class) {
			if(in_array($class, $this->dropdown_menu_alignment_values)) {
				$dropdown_menu_class[] = $class;
			}
		}
		$indent = str_repeat("\t", $depth);
		$submenu = ($depth > 0) ? ' sub-menu' : '';
		$display_depth = ($depth + 1);
		$classes = array(
			'',
			($display_depth >= 2 ? 'menu__subsub-list' : 'menu__sub-list'),
			'menu-depth-' . $display_depth
		);
		$class_names = implode(' ', $classes);

	
		// Init Mega Menu
		$normalmenu = false;
		$megamenu = get_field( 'megamenu', $this->current_item->ID );
		$parent_hasmegamenu = get_field( 'megamenu', $this->current_item->menu_item_parent );
		if( $megamenu )
			$this->current_megamenu = $megamenu;
	



		if( $megamenu && $depth < 1){
			$dropdown_menu_class[] = ' megamenu';
			// allineamento megamenu
			if($align = get_field( 'megamenu_alignment', $this->current_item->ID ))
				$dropdown_menu_class[] = 'megamenu-'.$align;
		}elseif ( $depth < 1 ) {
			$dropdown_menu_class[] = ' normalmenu';
			// allineamento centrale al item padre per i dropdown non megamenu
			$dropdown_menu_class[] = ' megamenu-centered';
			$normalmenu = true;
		}

		// $output .= $this->current_megamenu;
		
		// UL dropdown
		if($display_depth >= 2 && $this->current_megamenu && $this->current_megamenu!=='cld' ) {
			$submenu_id = $submenu.'-'.$this->current_item->ID;
			$output .= "\n$indent<ul class=\"$submenu $submenu_id $class_names depth_$depth\">\n";
		}else{
			$output .= "\n$indent<ul class=\"dropdown-menu$submenu $class_names" . esc_attr(implode(" ",$dropdown_menu_class)) . " depth_$depth\">\n";
		}

    if( $megamenu ){
		
      	$output .= '<div class="container my-3">';
      	$output .= '<div class="row d-flex flex-column flex-xl-row">';

		// incolonnati per items 1 livello
		if( $megamenu == 'cls' ){

			$output .= '<div class="col col-items">';
			$output .= '<div class="megamenu-col-wrapper d-flex p-lg-5">'; 

		// singola colonna con dropdown multi-livello
		}elseif( $megamenu == 'cld' ){

			$output .= '<div class="col col-items">';
			$output .= '<div class="megamenu-drophover-wrapper d-flex p-lg-5">'; 

		}else{
			// altri tipi da finire
			// $output .= '<div class="col-12 col-xl-7 megamenu-content order-1 py-4">';
			// $list_title = get_field( 'megamenu_item_title', $this->current_item->ID );
			// if( $list_title )
			// 	$list_title = '<h5 class="text-capitalize mb-4 megamenu-list-title">' . $list_title . '</h5>';
			
			// // items
			// $output .= '<div class="megamenu-items">';

		}

		$banners = get_field( 'megamenu_banners', $this->current_item->ID );
		if( $banners ){
            $count = ceil((count($banners)*3)/1.2);
            $output .= '<div class="col col-banners last d-flex">'; 
            foreach ($banners as $item) {
              $output .= '<div class="card-banner">';
               $output .= '<a class="card-banner-link" href="' . esc_url( $item['banner_cta']['url'] ) .'" target="' . esc_attr( $item['banner_cta']['target'] ) .'"> </a>';
              $output .= '<img src="' . esc_url( $item['banner_image']['url'] ) . '" class="card-banner-img rounded mb-1" alt="' . esc_attr( $item['banner_image']['alt'] ) . '">';
              $output .= '<div class="card-banner-content">';
              if($item['banner_title'])
                $output .= '<h6 class="mb-1 txt-uppercase txt-white">'.$item['banner_title'].'</h6>';
              if($item['banner_desc'])
                $output .= '<p class="bold mt-0 txt-white">'.$item['banner_desc'].'</p>';
              if($item['banner_cta'])
                $output .= '<a class="btn w-100 btn-outline-primary d-flex align-items-center" href="' . esc_url( $item['banner_cta']['url'] ) .'">' . esc_html( $item['banner_cta']['title'] ) . '<i class="icon-circle-arrow-right iconsize-4x ms-auto d-flex justify-content-center align-items-center"></i></a>';
              $output .= '</div>'; // chiudo card-banner-content
              $output .= '</div>';
            }
            $output .= '</div>';
        }
		if( $megamenu == 'cld' ){
		  $output .= '<div class="col col-items first d-flex flex-column">';
		}
		
    }elseif($normalmenu){
		$output .= '<div class="container">';
      	$output .= '<div class="row">';
		$output .= '<div class="col-12 menu-content">';
	}
  }

  	function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent  = str_repeat( $t, $depth );
    	$display_depth = ($depth + 1);

	
		// $megamenu = get_field( 'megamenu', $this->current_item->menu_item_parent );
    	if( $depth < 1 ){

			// chiusura megamenu
			if($this->current_megamenu){

				if( $this->current_megamenu == 'cls'){
					$output .= '</div>'; // col
					$output .= '</div>'; // col-wrapper
				}elseif( $this->current_megamenu == 'cld' ){
					$output .= '</div>'; // col-items 
					$output .= '</div>'; // col
					$output .= '</div>'; // col-wrapper
				}

				// chiusura megamenu
        		$output .= '</div>'; // row
        		$output .= '</div>'; // container

				$this->current_megamenu = null;

			// chiusura normalmenu
			}else{
				$output .= '</div>'; // menu-content
				$output .= '</div>'; // row
				$output .= '</div>'; // container
			}

			
			// else{
			// 	$output .= '</div>'; // megamenu-items
      		// 	$output .= '</div>'; // megamenu-content
			// }
        	
		}
		    
		$output .= "$indent</ul>{$n}";
  	}

  	function start_el(&$output, $item, $depth = 0, $args = null, $id = 0){
		$this->current_item = $item;
		

		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$li_attributes = '';
		$class_names = $value = '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;

		$classes[] = ($args->walker->has_children) ? 'dropdown' : '';
		$classes[] = 'nav-item';
		$classes[] = 'nav-item-' . $item->ID;
		if ($depth && $args->walker->has_children) {
		//$classes[] = 'dropdown-menu dropdown-menu-end';
		}



		// Megamenu
		$megamenu = get_field( 'megamenu', $item->ID );
		// is parent megamenu
		$parent_hasmegamenu = get_field( 'megamenu', $item->menu_item_parent );
		// item vars 
		$image = get_field( 'megamenu_item_image', $item->ID );
		$subtitle = get_field( 'megamenu_item_desc', $item->ID );
		$icon = get_field( 'megamenu_item_icon', $item->ID );
		$label = get_field( 'megamenu_item_label', $item->ID );
		$cta = get_field( 'megamenu_item_cta', $item->ID );
		$ctabg = get_field( 'megamenu_item_cta_background', $item->ID );

		if( $this->current_megamenu && ($this->current_megamenu=='cls' || $this->current_megamenu=='cld') && $depth == 1 && $image){

			$output .= '<div class="d-flex flex-column">';
			if( $cta ){
				$output .= '<div class="order-3 mt-auto">';
				$clcta = 'btn btn-primary d-flex align-items-center';
				$stcta = '';
				if($ctabg){
					// $clcta .= ' ';
					$stcta = 'background-image: url('.$ctabg['url'].'); background-repeat:no-repeat; background-size: cover;';
				}
				$output .= '<a href="' . $cta['url'] . '" class="'.$clcta.'" style="'.$stcta.'">';
				$output .= $cta['title'];
				$output .= '<i class="icon-circle-arrow-right iconsize-4x ms-auto d-flex justify-content-center align-items-center"></i>';
				$output .= '</a>';
				$output .= '</div>';
			$output .= '<div class="d-flex column-gap-3 mb-3">';
			}else{
			$output .= '<div class="d-flex column-gap-3">';
			}

			$output .= '<div class="order-2 menu-item-image">';
			$output .= '<img class="img-fluid min-image rounded" src="'.$image['url'].'" alt="'.$image['alt'].'" >';
			$output .= '</div>';
			$classes[] = 'flex-fill';

			
			
		}
		

		if( $this->current_megamenu && $depth < 1 ){
			$classes[] = 'has-megamenu';
		}

		// se ha parent aggiungo classe
		if( $item->menu_item_parent )
			$classes[] = 'parent-item-'.$item->menu_item_parent;

		if($item->current || $item->current_item_ancestor || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)){
			$classes[] = 'li-active';
		}
		if( $this->current_megamenu && $depth == 1){
			$li_attributes .= ' data-itemid="'.$item->ID.'"';
		}
		$class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
		$class_names = ' class="' . esc_attr($class_names) . '"';

		$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
		$id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

		$output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';
		
		$attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
		$attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
		$attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
		$attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

		$active_class = ($item->current || $item->current_item_ancestor && $depth !== 1 ) ? 'active' : '';// || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)
		$nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link link-body-emphasis ';
		
		$attributes .= ( ($args->walker->has_children && $depth < 1) || ($args->walker->has_children && $parent_hasmegamenu == 'cld') || ($args->walker->has_children && $this->current_megamenu =='cld' && $depth < 4) ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';

		$item_output = $args->before;

		

		if( $this->current_megamenu && $depth == 1){
			
			
			
			// $item_output .= '<span class="d-block fw-semibold" '.$data_parent.'>';
			// if($icon)
			// 	$item_output .= ' <i class="position-absolute ms-neg-4 text-danger '.$icon.'"></i>';

			// $item_output .= '</span>';
			if($this->current_megamenu == 'cls'){
				$this->column_item = $item;
				$item_output .= '<h4 class="text-nowrap fs-lg mb-3 d-block fw-semibold">';
			}else{
				$item_output .= '<a' . $attributes . '>';
				if($args->walker->has_children):
					$item_output .= '<span class="">';
				else:
					$item_output .= '<span class="d-block">';
				endif;
			}
			// $item_output .= '<img class="icon" src="'.$icon.'">';
			$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
			
			if( $label ){
				$item_output .= '<span class="position-absolute m-0 ms-2 badge rounded-pill text-bg-news">'.$label.'</span>';
			}
			
			if( $subtitle ){
				$item_output .= '<span class="d-block fs-xs">';
				$item_output .= $subtitle;
				$item_output .= '</span>';
			}
			if( $this->current_megamenu == 'cls' ) {
				$item_output .= '</h4>';
			}else{
				$item_output .= '</span>';
				$item_output .= '</a>';
			}
				
		}else{
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
			$item_output .= '</a>';
		}
    
    	$item_output .= $args->after;

    	$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

  	function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		
		$output .= "</li>{$n}";

		$column_item = $this->column_item;
		// chiude colonna dentro megamenu-col-wrapper per immagine 1° item
		if($column_item && $depth == 1){
			
			$image = get_field( 'megamenu_item_image', $column_item->ID );

			if( $this->current_megamenu && $this->current_megamenu == 'cls' && $image){
				$output .= "</div></div>{$n}";
			}
			$this->column_item = null;
		}

	}

  
}

class NAVWALKER_mobile extends Walker_Nav_menu {
  private $current_item;
  private $menu_type = array();
  private $dropdown_menu_alignment_values = [
    'dropdown-menu-start',
    'dropdown-menu-end',
    'dropdown-menu-sm-start',
    'dropdown-menu-sm-end',
    'dropdown-menu-md-start',
    'dropdown-menu-md-end',
    'dropdown-menu-lg-start',
    'dropdown-menu-lg-end',
    'dropdown-menu-xl-start',
    'dropdown-menu-xl-end',
    'dropdown-menu-xxl-start',
    'dropdown-menu-xxl-end'
  ];

  public function start_lvl(&$output, $depth = 0, $args = null)
		{
			if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = str_repeat($t, $depth);
			// Default class to add to the file.
			$classes = array('dropdown-menu');

			// add class menu mobile dropdown
			$classes[] = 'mobilemenu';
			/**
			 * Filters the CSS class(es) applied to a menu list element.
			 *
			 * @since WP 4.8.0
			 *
			 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
			 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
			$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

			/*
			 * The `.dropdown-menu` container needs to have a labelledby
			 * attribute which points to it's trigger link.
			 *
			 * Form a string for the labelledby attribute from the the latest
			 * link with an id that was added to the $output.
			 */
			
			$output .= "{$n}{$indent}<ul$class_names>{$n}";
		}

  

  function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
    $this->current_item = $item;

    $indent = ($depth) ? str_repeat("\t", $depth) : '';

    $li_attributes = '';
    $class_names = $value = '';

    $classes = empty($item->classes) ? array() : (array) $item->classes;

    $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
    $classes[] = 'nav-item';
    $classes[] = 'nav-item-' . $item->ID;
    if ($depth && $args->walker->has_children) {
      //$classes[] = 'dropdown-menu dropdown-menu-end';
    }

    

	// se ha parent aggiungo classe
	if( $item->menu_item_parent )
		$classes[] = 'parent-item-'.$item->menu_item_parent;

    if($item->current || $item->current_item_ancestor || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)){
		$classes[] = 'li-active';
	}
    $class_names =  join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = ' class="' . esc_attr($class_names) . '"';

    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
    $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

	$output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';
	
    $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
    $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
    $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
    $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
	
    $active_class = ($item->current || $item->current_item_ancestor ) ? 'active' : '';// || in_array("current_page_parent", $item->classes, true) || in_array("current-post-ancestor", $item->classes, true)
    $nav_link_class = ( $depth > 0 ) ? 'dropdown-item ' : 'nav-link link-body-emphasis ';
    
	
	
	$attributes .= ( $args->walker->has_children ) ? ' class="'. $nav_link_class . $active_class . ' dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="'. $nav_link_class . $active_class . '"';

    $item_output = $args->before;

	
		$item_output .= '<a' . $attributes . '>';
		$item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
		$item_output .= '</a>';
	
    
    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }
}
}
