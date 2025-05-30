<?php

/**
 * Hero Block Template.
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
$class_name = 'block block__hero position-relative py-7 py-lg-10';
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
$title = get_field('hero_title');
$button = get_field('hero_button');

?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> video-container">
    <?php 
    $video_url = get_field('hero_video_url'); 
    $background_image = get_field('hero_background_image'); // campo immagine ACF (tipo: immagine)

    if ($video_url) :
        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_url, $matches);
        if (!empty($matches[1])) :
            $video_id = $matches[1];
    ?>
        <div class="video-wrapper">
            <div class="video-container">
                <iframe 
                    src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>?autoplay=1&mute=1&controls=0&modestbranding=1&showinfo=0&rel=0&playsinline=1&disablekb=1&loop=1&playlist=<?php echo esc_attr($video_id); ?>" 
                    frameborder="0" 
                    allow="autoplay; fullscreen" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    <?php 
        endif;
    elseif ($background_image) :
        $image_url = $background_image['url'];
    ?>
        <div class="video-wrapper">
            <div class="image-background" style="background-image: url('<?php echo esc_url($image_url); ?>');">
                <!-- Eventuale contenuto sovrapposto -->
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-lg-6">
                <?php if (!empty($title)) : ?>
                    <h1 class="mb-6 text-white"><?php echo esc_html($title); ?></h1>
                <?php endif; ?>

                <?php 
                if (!empty($button) && is_array($button) && isset($button['url'], $button['title'])) : ?>
                    <a href="<?php echo esc_url($button['url']); ?>" 
                       class="btn btn-primary animate__animated animate__delay-1s"
                       data-scroll data-scroll-class="animate__fadeInLeft" 
                       style="--animate-delay:0.8s"
                       <?php echo !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                        <?php echo esc_html($button['title']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
