<?php
namespace TST;
if( ! class_exists( '\TST\GF' ) ) {
class GF {
    
    public function __construct (){
		// auto gclid
        add_filter( 'gform_field_value', [$this, 'auto_gclid'], 999, 3 );
    
		// cpt in select
		// add_filter('gform_pre_render', [$this, 'populate_cpt_select'], 99, 3);
		// add_filter('gform_pre_validation', [$this, 'populate_cpt_select'], 99, 3);
		// add_filter('gform_pre_submission_filter', [$this, 'populate_cpt_select'], 99, 3);

		// tax in select
		// add_filter('gform_pre_render', [$this, 'populate_tax_select'], 99, 3);
		// add_filter('gform_pre_validation', [$this, 'populate_tax_select'], 99, 3);
		// add_filter('gform_pre_submission_filter', [$this, 'populate_tax_select'], 99, 3);

		
	}

    public function auto_gclid ( $value, $field, $name ){
        if ( isset( $_COOKIE['tw-gclid'] ) && $name == "gclid" && $field->type == "hidden" )
            $value = $_COOKIE['tw-gclid'];            
        
        return $value;
    }

	public function populate_tax_select( $form, $ajax, $field_values ) {
		foreach ( $form['fields'] as &$field ) {
			if ( $field->type !== 'select' || strpos( $field->cssClass, 'populate_tax_' ) === false ) {
					continue;
			}

			$elements = explode( '_', $field->cssClass );
			$tax = end( $elements );

			$field->placeholder = sprintf( trad___('Seleziona %s'), $tax);

			if(!taxonomy_exists($cpt)){
				continue;
			}

			$args = [
				'taxonomy'   => $tax,
    			'hide_empty' => false,
			];
			$terms = get_terms( $args );

			$type = 'default';
			$tterms = [];
			if( isset($field_values['include_tax'])){
				$type = 'include';
				$tterms = explode(',', $field_values['include_tax']);
			}elseif ( isset($field_values['exclude_tax'])) {
				$type = 'exclude';
				$tterms = explode(',', $field_values['exclude_tax']);
			}

			$options = [];
			foreach( $terms as $term ) {
				$value = $term->name;
				$res = $this->string_in_array($value, $tterms);

				if( $type == 'include' && $res )
				{
					$options[] = ['text' => $value, 'value' => $value];
				}
				elseif ( $type == 'exclude' && !$res ) 
				{
					$options[] = ['text' => $value, 'value' => $value];
				}
				elseif ( $type == 'default' )
				{
					$options[] = ['text' => $value, 'value' => $value];
				} 

			}

		}
	}

	public function populate_cpt_select( $form, $ajax, $field_values ) {
		foreach ( $form['fields'] as &$field ) {
			if ( $field->type !== 'select' || strpos( $field->cssClass, 'populate_cpt_' ) === false ) {
					continue;
			}
			
			
			$elements = explode( '_', $field->cssClass );
			$cpt = end( $elements );
			
			$field->placeholder = sprintf( trad___('Seleziona %s'), $cpt);

			if(!post_type_exists($cpt)){
				continue;
			}

			$args = [
				'posts_per_page'   => -1,
				'order'            => 'ASC',
				'orderby'          => 'post_title',
				'post_type'        => $cpt,
				'post_status'      => 'publish',
			];
			$custom_posts = get_posts( $args );

			$type = 'default';
			$cpts = [];
			if( isset($field_values['include_cpt'])){
				$type = 'include';
				$cpts = explode(',', $field_values['include_cpt']);
			}elseif ( isset($field_values['exclude_cpt'])) {
				$type = 'exclude';
				$cpts = explode(',', $field_values['exclude_cpt']);
			}

			$options = [];
			foreach( $custom_posts as $custom_post ) {
				$value = $custom_post->post_title;
				$res = $this->string_in_array($value, $cpts);

				if( $type == 'include' && $res )
				{
					$options[] = ['text' => $value, 'value' => $value];
				}
				elseif ( $type == 'exclude' && !$res ) 
				{
					$options[] = ['text' => $value, 'value' => $value];
				}
				elseif ( $type == 'default' )
				{
					$options[] = ['text' => $value, 'value' => $value];
				}        
			}

			$field->choices = $options;
		}

		return $form;
	}

	private function string_in_array($string, $arr) {
		foreach ($arr as $needle) {
			if(stripos($string, $needle) !== false)
				return true; 
		}
		return false;
	}
}
if (class_exists( 'GFForms' )) {
	add_action('init', function(){
		new GF();	
	});
}else {
	add_action( 'admin_notices', function(){
		?>
		<div class="notice notice-warning is-dismissible">
		<p><?php _e( 'Installare Plugin GravityForms' ); ?></p>
		</div>
		<?php
	} );
}
}