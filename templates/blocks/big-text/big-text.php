<?php

/**
 * Big Text Block Template.
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
$class_name = 'block block__big-text position-relative';
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

// ACF field
$label = get_field('label');
$title = get_field('title');


?>
<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container">
        <div class="row justify-content-md-end">
            <div class="col-xs-12 col-md-10 col-lg-7">
                <div class="d-flex align-items-center justify-content-between my-5">
                    <div class="circle"></div>
                    <p class="m-0 text-uppercase" ><?php echo $label; ?></p>
                    <div class="line border"></div>
                </div>
                <h2 class="animate__animated animate__delay-1s" data-scroll data-scroll-class="animate__fadeIn" style="--animate-delay:0.4s;"><?php echo $title; ?></h2>
            </div>
        </div>
    </div>

</section>