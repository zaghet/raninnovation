<?php

/**
 * Hero Slider Block Template.
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
$class_name = 'block block__hero block__hero-slider position-relative';
if( !empty($block['className']) ) {
    $class_name .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $class_name .= ' block-align-' . $block['align'];
}
if( $is_preview ) {
    $class_name .= ' is-admin';
}
if( isset($block['full_height'] )) {
    $class_name .= ' vh-100';
}

?>
<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <?php if (have_rows('hero_slider')) : ?>

    <div class="hero-slider swiper">

        <div class="swiper-wrapper">

            <?php while (have_rows('hero_slider')) : the_row(); ?>
            <?php 
            $slide = get_sub_field('slide');
            if ($slide) :
                $color_text = $slide['color_text']; 
                $title = $slide['title'];
                $link = $slide['link'];
                $image = $slide['image']; 
                if($image){
                    $image_url = wp_get_attachment_image_url( $image['ID'], 'hero' );
                }else {
                    $image_url = '';
                }

                $video = $slide['video']; 

                $text_class_color = ($color_text === 'Bianco') ? 'text-white' : ''; 

            ?>
            <div class="slide swiper-slide">
                <div class="d-flex flex-column h-100 align-items-center justify-content-end position-relative z-2">
                    <?php if ($title) : ?>
                    <h1 class="mb-5 mb-lg-9 <?php echo esc_attr($text_class_color); ?>"><?php echo esc_html($title); ?>
                    </h1>
                    <?php endif; ?>
                    <?php 
                    if( $link ): 
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                    <a class="text-uppercase text-decoration-none <?php echo esc_attr($text_class_color); ?>"
                        href="<?php echo esc_url( $link_url ); ?>"
                        target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
                    <i class="icon-arrow-down-slider"></i>
                    <?php endif; ?>
                </div>
                <?php if( $image): ?>
                <div class="wrap-img">
                    <img src="<?php echo $image_url; ?>" />
                </div>
                <?php endif; ?>
                <?php if( $video ): ?>
                <div class="wrap-video w-100">
                    <video class="w-100" playsinline autoplay loop muted>
                        <source src="<?php echo $video ?>" type="video/mp4">
                    </video>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endwhile; ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <?php endif; ?>
</section>