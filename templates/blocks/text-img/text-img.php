<?php

/**
 * Text and Image Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */

 // Create id attribute allowing for custom "anchor" value.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'block block__text-img position-relative py-7 py-lg-10';
if( !empty($block['className']) ) {
    $class_name .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $class_name .= ' block-align-' . $block['align'];
}
if( $is_preview ) {
    $class_name .= ' is-admin';
}
if( isset($block['full_height']) ) {
    $class_name .= ' vh-100';
}

//  get field
$icon = get_field('text-img_icon');
$text = get_field('text-img_text');
$img = get_field('text-img_image');

?>
<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-5 p-10">
                <?php if (!empty($icon)): ?>
                    <img src="<?php echo esc_url($icon['url']); ?>" class="icon pb-5" alt="<?php echo esc_attr($icon['alt']); ?>" />
                <?php endif; ?>
                <?php if ($text): ?>
                    <div><?php echo wp_kses_post($text); ?></div>
                <?php endif; ?>
            </div>
            <div class="col-xs-12 col-md-7 p-0">
                <?php if (!empty($img)): ?>
                    <img src="<?php echo esc_url($img['url']); ?>" class="image" alt="<?php echo esc_attr($img['alt']); ?>" />
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>