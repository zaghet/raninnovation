<?php

/**
 * Hero Page Block Template.
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
$class_name = 'block block__hero-page position-relative py-7 py-lg-10';
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
$title = get_field('hero-page_title');
$text = get_field('hero-page_text');
$button = get_field('hero-page_button');
$certifications = get_field('hero-page_certifications');
$text_2 = get_field('hero-page_text_2');
$button_2 = get_field('hero-page_button_2');
$img = get_field('hero-page_img');
$img_url = $img ? esc_url($img['url']) : '';
$bg_style = $img_url ? "background-image: url('$img_url'); background-size: cover; background-position: center;" : "";
$cards = get_field('hero-page_cards');
$text_3 = get_field('hero-page_text_3');
$img_2 = get_field('hero-page_img_2');
$show_after = get_field('show_overlay');
$rectangles = get_field('hero-page_rectangles');
$has_rectangles = !empty($rectangles);
if (!empty($rectangles)) {
    $bg_style .= ' margin-bottom: 100px;';
} else {
    $bg_style .= ' margin-bottom: 0;';
}
?>

<section <?php echo $anchor; ?> 
    class="<?php echo esc_attr($class_name); ?>" 
    data-animation="animate__fadeIn"
    style="<?php echo esc_attr($bg_style); ?>">
    
    <div class="bg-overlay <?php echo $show_after ? 'show-after' : ''; ?>"></div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-lg-6 left">
                <h1 class="mb-6"><?php echo esc_html($title); ?></h1>

                <?php if (!empty($text)) : ?>
                    <div class="mb-6"><?php echo wp_kses_post($text); ?></div>
                <?php endif; ?>

                <?php if (!empty($button['url']) && !empty($button['title'])) : ?>
                    <a href="<?php echo esc_url($button['url']); ?>" 
                    class="btn btn-primary animate__animated animate__delay-1s"
                    data-scroll data-scroll-class="animate__fadeInLeft" 
                    style="--animate-delay:0.8s"
                    <?php echo !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                        <?php echo esc_html($button['title']); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="col-xs-12 col-lg-6 right">
                <?php if ($certifications): ?>
                    <div>
                        <?php foreach ($certifications as $certification): ?>
                            <div class="certifications">
                                <?php echo $certification['hero-page_text_2']; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($button_2['url']) && !empty($button['title'])) : ?>
                    <a href="<?php echo esc_url($button_2['url']); ?>" 
                    class="btn btn-outline-primary-yellow animate__animated animate__delay-1s"
                    data-scroll data-scroll-class="animate__fadeInLeft" 
                    style="--animate-delay:0.8s"
                    <?php echo !empty($button_2['target']) ? 'target="' . esc_attr($button_2['target']) . '"' : ''; ?>>
                        <?php echo esc_html($button_2['title']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if (!empty($rectangles) && is_array($rectangles)): ?>
        <div class="row above">
            <?php foreach ($rectangles as $rectangle): 
                $rectangle_text = $rectangle['hero-page_rectangles_text'] ?? ''; 
                $rectangle_img = $rectangle['hero-page_rectangles_img'] ?? null;
            ?>
                <div class="rectangle">
                    <?php if (!empty($rectangle_img)): ?>
                        <img src="<?php echo esc_url($rectangle_img['url']); ?>" alt="<?php echo esc_attr($rectangle_img['alt']); ?>" />
                    <?php endif; ?>

                    <?php if (!empty($rectangle_text)): ?>
                        <div>
                            <?php echo wp_kses_post($rectangle_text); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>       