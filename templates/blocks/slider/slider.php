<?php
/**
 * Slider Block Template.
 * 
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block
 */

// Create id attribute allowing for custom "anchor" value.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'block__slider py-3 py-lg-5';
if( !empty($block['className']) ) {
    $class_name .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $class_name .= ' block-align-' . $block['align'];
}
if( !empty($block['align_text']) ) {
    $class_name .= ' block-align_text-' . $block['align'];
}
if( !empty($block['align_content']) ) {
    $class_name .= ' block-align_content-' . $block['align'];
}


if( $is_preview ) {
    $class_name .= ' is-admin';
}
//  get field
?>
<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container">
        <div class="row">
            <div class="swiper slider-images ps-2 ps-md-0 pb-8">
                <div class="swiper-wrapper">
                    <?php if (have_rows('slider_images')):
                        while (have_rows('slider_images')) : the_row();
                            $img = get_sub_field('img');
                    ?>
                            <div class="swiper-slide">
                                <?php if (!empty($img)): ?>
                                    <div class="ratio ratio-4x3">
                                        <img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" class="img-fluid object-fit-cover" />
                                    </div>
                                <?php endif; ?>
                            </div>
                    <?php
                        endwhile;
                    else :
                    endif;
                    ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</section>