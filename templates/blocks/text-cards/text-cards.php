<?php

/**
 * Text and Cards Block Template.
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
$class_name = 'block block__text-cards position-relative py-7 py-lg-10';
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
$title = get_field('text-cards_title');
$button = get_field('text-cards_button');
$text = get_field('text-cards_text');

?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-lg-4 left">
                <?php if( !empty($title) ): ?>
                    <h2 class="mb-6"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                
                <?php if( !empty($text) ): ?>
                    <p><?php echo wp_kses_post($text); ?></p>
                <?php endif; ?>
                
                <?php if( !empty($button) && isset($button['url'], $button['title']) ): ?>
                    <a href="<?php echo esc_url($button['url']); ?>" 
                       class="btn btn-primary animate__animated animate__delay-1s mt-6"
                       data-scroll data-scroll-class="animate__fadeInLeft" 
                       style="--animate-delay:0.8s"
                       <?php echo !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                        <?php echo esc_html($button['title']); ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="col-xs-12 col-lg-8 right">
                <?php if( have_rows('text-cards_section') ): ?>
                    <div class="row cards">
                        <?php while( have_rows('text-cards_section') ): the_row(); 
                            // Recupera i valori dei singoli campi
                            $icon = get_sub_field('text-cards_section_icon');
                            $section_text = get_sub_field('text-cards_section_txt');
                        ?>
                            <div class="card">
                                <?php if( $icon ): ?>
                                    <div class="icon">
                                        <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>" />
                                    </div>
                                <?php endif; ?>

                                <?php if( $section_text ): ?>
                                    <div class="text">
                                        <?php echo wp_kses_post($section_text); // Protegge il contenuto wysiwyg ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
