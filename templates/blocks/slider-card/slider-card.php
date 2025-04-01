<?php
/**
 * Slider Card Block Template.
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
$class_name = 'block__slidercard py-3 pb-7';
if( !empty($block['className']) ) {
    $class_name .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $class_name .= ' block-align-' . $block['align'];
}
if( !empty($block['align_text']) ) {
    $class_name .= ' block-aligntext-' . $block['align'];
}
if( !empty($block['align_content']) ) {
    $class_name .= ' block-aligncontent-' . $block['align'];
}


if( $is_preview ) {
    $class_name .= ' is-admin';
}
//  get field
?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container">
        <div class="row">
            <div class="swiper slider-cards pb-7">
                <div class="swiper-wrapper">
                    <?php if (have_rows('slider_cards')):
                        while (have_rows('slider_cards')) : the_row();
                            $card_icon = get_sub_field('card_icon');
                            $card_title = get_sub_field('card_title');
                            $card_text = get_sub_field('card_text');
                    ?>
                            <div class="swiper-slide ps-2 ps-lg-0">
                                <div class="card card-1 h-100">
                                    <div class="card-body px-4 py-6">
                                        <?php
                                        if (!empty($card_icon)): ?>
                                            <img src="<?php echo esc_url($card_icon['url']); ?>" alt="<?php echo esc_attr($card_icon['alt']); ?>" height="50" width="50"/>
                                        <?php endif; ?>
                                        <h5 class="card-title lh-sm my-4"><?php echo $card_title; ?></h5>
                                        <?php echo $card_text; ?>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endwhile;
                    endif;
                    ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</section>