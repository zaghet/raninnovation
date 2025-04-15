<?php

/**
 * Gallery Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or its parent block.
 */

// Create id attribute allowing for custom "anchor" value.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'block block__gallery position-relative py-7 py-lg-10';
if( !empty($block['className']) ) {
    $class_name .= ' ' . esc_attr($block['className']);
}
if( !empty($block['align']) ) {
    $class_name .= ' block-align-' . esc_attr($block['align']);
}
if( $is_preview ) {
    $class_name .= ' is-admin';
}
if( isset($block['full_height']) ) {
    $class_name .= ' vh-100';
}

// Get fields
$title = get_field('title_gallery');
$text = get_field('text_gallery');
$slider = get_field('slider_gallery');
$image = get_field('image_gallery');

?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr($class_name); ?>">
    <div class="container">
        <div class="row">
            <?php if ($title || $text): ?>
                <div class="col-12 mb-4 text-center">
                    <?php if ($title): ?>
                        <h2 class="h3"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                    <?php if ($text): ?>
                        <div><?php echo wp_kses_post($text); ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (have_rows('slider_gallery')): ?>
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php while (have_rows('slider_gallery')): the_row(); 
                            $slide_image = get_sub_field('image');
                        ?>
                            <div class="swiper-slide">
                                <?php if ($slide_image): ?>
                                    <img src="<?php echo esc_url($slide_image['url']); ?>" alt="<?php echo esc_attr($slide_image['alt']); ?>" />
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Swiper Controls -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-pagination"></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
