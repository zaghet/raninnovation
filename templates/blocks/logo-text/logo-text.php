<?php

/**
 * Logo and Text Block Template.
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
$class_name = 'block block__logo-text position-relative';
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
$repeater = get_field('repeater_logo-text');

?>
<section <?php echo $anchor; ?> class="<?php echo esc_attr($class_name); ?>">
    <div class="container">
        <?php if (have_rows('repeater_logo-text')): ?>
            <div class="row">
                <?php while (have_rows('repeater_logo-text')): the_row(); 
                    $img = get_sub_field('img_logo-text');
                    $text = get_sub_field('text_logo-text');
                    $button = get_sub_field('btn_logo-text');
                ?>
                    <div class="col-12 col-md-6 mb-4">
                        <div class="repeater-item p-5">
                            <?php if (!empty($img)): ?>
                                <img src="<?php echo esc_url($img['url']); ?>" class="w-50 py-5" alt="<?php echo esc_attr($img['alt']); ?>" />
                            <?php endif; ?>

                            <?php if ($text): ?>
                                <div class="py-5"><?php echo wp_kses_post($text); ?></div>
                            <?php endif; ?>

                            <?php if (!empty($button) && isset($button['url'], $button['title'])): ?>
                                <a href="<?php echo esc_url($button['url']); ?>" 
                                   class="btn btn-download animate__animated animate__delay-1s align-self-start"
                                   data-scroll data-scroll-class="animate__fadeInLeft" 
                                   style="--animate-delay:0.8s"
                                   <?php echo !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                                    <?php echo esc_html($button['title']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
