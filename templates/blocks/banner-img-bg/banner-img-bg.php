<?php

/**
 * Banner Image Background Template.
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
$class_name = 'block block__banner-img-bg position-relative';
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


// ACF fields
$title   = get_field('banner-img-bg_title');
$text    = get_field('banner-img-bg_text');
$button  = get_field('banner-img-bg_button');
$img = get_field('banner-img-bg_img');
$img_url = $img ? esc_url($img['url']) : '';
$bg_style = $img_url ? "background-image: url('$img_url'); background-size: cover; background-position: center;" : "";

?>

<section <?php echo $anchor; ?> 
    class="<?php echo esc_attr($class_name); ?>" 
    data-animation="animate__fadeIn"
    style="<?php echo esc_attr($bg_style); ?>">
    <div class="bg-overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-6 mb-5">
                    <?php if ($title): ?>
                        <h1><?php echo esc_html($title); ?></h1>
                    <?php endif; ?>
                </div> 
                <div class="col-md-12 col-lg-6">
                <?php if ($text): ?>
                    <div class="mb-5"><?php echo wp_kses_post($text); ?></div>
                <?php endif; ?>
                    
                    <?php if (!empty($button) && isset($button['url'], $button['title'])): ?>
                        <a href="<?php echo esc_url($button['url']); ?>" 
                        class="btn btn-secondary animate__animated animate__delay-1s"
                        data-scroll data-scroll-class="animate__fadeInLeft" 
                        style="--animate-delay:0.8s"
                        <?php echo !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                            <?php echo esc_html($button['title']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
