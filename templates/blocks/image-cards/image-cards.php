<?php

/**
 * Image Cards Block Template.
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
$class_name = 'block block__image-cards position-relative';
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
$label = get_field('label');
$title = get_field('title');
$txt = get_field('txt', false, false);


?>
<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between my-5">
            <div class="circle"></div>
            <p class="m-0 text-uppercase"><?php echo $label; ?></p>
            <div class="line border"></div>
        </div>
        <div class="row align-items-center mb-7">
            <div class="col-xs-12 col-md-6">
                <h2 class="animate__animated animate__delay-1s" data-scroll data-scroll-class="animate__fadeIn"
                    style="--animate-delay:0.4s">
                    <?php echo $title; ?></h2>
            </div>
            <div class="col-xs-12 col-md-6">
                <p class="animate__animated animate__delay-1s" data-scroll data-scroll-class="animate__fadeInRight"
                    style="--animate-delay:0.6s"><?php echo $txt; ?></p>
            </div>
        </div>
        <?php if( have_rows('image_cards') ): ?>
        <div class="d-flex align-items-center gap-4 flex-wrap flex-lg-nowrap">
            <?php $i=0; while( have_rows('image_cards') ) : the_row();
              $card = get_sub_field('card');
              $i++;
            if ($card) :
                $card_title = $card['card_title'];
                $card_txt = $card['card_txt'];
                $card_link = $card['card_link']; 
                $bg_image = $card['bg_image'];
             ?>
            <div class="card-image position-relative overflow-x-hidden rounded animate__animated animate__delay-<?php echo $i-1;?>s" data-scroll
                data-scroll-class="animate__fadeInUp"
                style="--animation-delay:0.5s; background-image:url(<?php echo $bg_image; ?>)">
                <div class="card-content position-absolute">
                    <div class="wrap-content">
                        <h3 class="text-white mb-5 fw-semibold"><?php echo $card_title; ?></h3>
                        <div
                            class="d-flex align-items-center justify-content-end justify-content-lg-between flex-wrap flex-lg-nowrap">
                            <p class="text-white"><?php echo $card_txt; ?></p>
                            <?php 
                            if( $card_link ): 
                            $link_url = $card_link['url'];
                            $link_title = $card_link['title'];
                            $link_target = $card_link['target'] ? $card_link['target'] : '_self';
                            ?>
                            <a class="btn btn-secondary d-flex align-items-center text-nowrap"
                                href="<?php echo esc_url( $link_url ); ?>"
                                target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?>
                                <i
                                    class="icon-arrow-right bg-dark text-white ms-3 rounded p-1 d-flex justify-content-center align-items-center"></i>
                            </a>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>

</section>