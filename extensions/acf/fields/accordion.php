<?php
add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_67543e96c3c74',
	'title' => 'Block: Accordion',
	'fields' => array(
		array(
			'key' => 'field_67543e9639623',
			'label' => 'Titolo',
			'name' => 'accordion_title',
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
			'key' => 'field_6754409216490',
			'label' => 'Testo intro',
			'name' => 'accordion_desc',
			'aria-label' => '',
			'type' => 'textarea',
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
			'rows' => '',
			'placeholder' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_67543e9_button_accordion', // Unico ID per il campo bottone
			'label' => 'Bottone',
			'name' => 'accordion_button',
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
		array(
			'key' => 'field_67543f6c93883',
			'label' => 'Sezioni',
			'name' => 'accordion_sections',
			'aria-label' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'block',
			'pagination' => 0,
			'min' => 0,
			'max' => 0,
			'collapsed' => '',
			'button_label' => 'Aggiungi Riga',
			'rows_per_page' => 20,
			'sub_fields' => array(
				array(
					'key' => 'field_67543f8e93884',
					'label' => 'Nome sezione',
					'name' => 'accordion_item_title',
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
					'parent_repeater' => 'field_67543f6c93883',
				),
				array(
					'key' => 'field_67543f9493885',
					'label' => 'Contenuto sezione',
					'name' => 'accordion_item_desc',
					'aria-label' => '',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'allow_in_bindings' => 0,
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 1,
					'delay' => 0,
					'parent_repeater' => 'field_67543f6c93883',
				),
				array(
					'key' => 'field_67543f9a_section_link',
					'label' => 'Link sezione',
					'name' => 'accordion_item_link',
					'type' => 'link',
					'instructions' => 'Aggiungi un link per la sezione.',
					'return_format' => 'array', // Così restituisce URL, titolo e target
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'block',
				'operator' => '==',
				'value' => 'acf/acf-accordion',
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