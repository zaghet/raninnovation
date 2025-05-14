<?php

/**
 * Slider Logo Block Template.
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
$class_name = 'block block__slider-logo position-relative py-7 py-lg-10';
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
$title = get_field('slider-logo_title');
$text = get_field('slider-logo_text');
$images = get_field('slider-logo_slider');
$image = get_field('slider-logo_logo');
$button = get_field('slider-logo_btn');

?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container d-flex flex-column align-items-center gap-4">
        <?php if ($title) : ?> 
            <h2 class="slider-title text-center"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>

        <?php if ($text) : ?>
            <div class="slider-text text-center"><?php echo wp_kses_post($text); ?></div>
        <?php endif; ?>

        <?php if (!empty($images) && is_array($images)): ?>
        <div class="swiper image-slider">
            <div class="swiper-wrapper">
            <?php foreach ($images as $image): ?>
                <div class="swiper-slide">
                <?php if (!empty($image['slider-logo_logo']['url'])): ?>
                    <img src="<?php echo esc_url($image['slider-logo_logo']['url']); ?>" 
                        alt="<?php echo esc_attr($image['slider-logo_logo']['alt']); ?>" />
                <?php endif; ?>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
            <!-- <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div> -->
        </div>
        <?php endif; ?>

        <a href="<?php echo esc_url($button['url']); ?>" 
            class="btn btn-primary animate__animated animate__delay-1s"
            data-scroll data-scroll-class="animate__fadeInLeft" 
            style="--animate-delay:0.8s"
            <?php echo !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
            <?php echo esc_html($button['title']); ?>
        </a>
    </div>
</section>