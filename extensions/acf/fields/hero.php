<?php
add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_66f2ae96ee1ee',
	'title' => 'Block: Hero',
	'fields' => array(
		array(
			'key' => 'field_66f2af129d47a',
			'label' => 'Titolo',
			'name' => 'hero_title',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_67111c9abutton', // Unico ID per il campo bottone
				'label' => 'Bottone',
				'name' => 'hero_button',
				'aria-label' => '',
				'type' => 'link', // Tipo di campo "Link"
				'instructions' => 'Aggiungi un link per il bottone.',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'return_format' => 'array', // Ritorna un array con URL, titolo e target
			),
		// 	'key' => 'field_66f2af429d47b',
		// 	'label' => 'Testo',
		// 	'name' => 'hero_text',
		// 	'aria-label' => '',
		// 	'type' => 'wysiwyg',
		// 	'instructions' => '',
		// 	'required' => 0,
		// 	'conditional_logic' => 0,
		// 	'wrapper' => array(
		// 		'width' => '',
		// 		'class' => '',
		// 		'id' => '',
		// 	),
		// 	'default_value' => '',
		// 	'allow_in_bindings' => 0,
		// 	'tabs' => 'all',
		// 	'toolbar' => 'basic',
		// 	'media_upload' => 0,
		// 	'delay' => 0,
		// ),
		// array(
		// 	'key' => 'field_67111b8fe76ed',
		// 	'label' => 'Immagine Hero',
		// 	'name' => 'image',
		// 	'aria-label' => '',
		// 	'type' => 'image',
		// 	'instructions' => '',
		// 	'required' => 0,
		// 	'conditional_logic' => 0,
		// 	'wrapper' => array(
		// 		'width' => '',
		// 		'class' => '',
		// 		'id' => '',
		// 	),
		// 	'return_format' => 'array',
		// 	'library' => 'all',
		// 	'min_width' => '',
		// 	'min_height' => '',
		// 	'min_size' => '',
		// 	'max_width' => '',
		// 	'max_height' => '',
		// 	'max_size' => '',
		// 	'mime_types' => '',
		// 	'allow_in_bindings' => 0,
		// 	'preview_size' => 'medium',
		// ),
	),
	'location' => array(
		array(
			array(
				'param' => 'block',
				'operator' => '==',
				'value' => 'acf/acf-hero',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );
} );