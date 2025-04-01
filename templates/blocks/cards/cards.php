<?php

/**
 *  Cards Block Template.
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
$class_name = 'block block__cards position-relative py-7 py-lg-10';
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
$title = get_field('title');


?>
<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container rounded bg-light p-5 p-lg-10">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="h3"><?php echo $title; ?></h2>
            </div>
        </div>
        <?php if( have_rows('cards') ): 
            $counter = 1; ?>
        <div class="row mt-5">
            <?php $i=0;  while( have_rows('cards') ) : the_row();
                    $i++;
                    $card = get_sub_field('card');
                    if($card): 
                    $title = $card['title']; 
                    $txt = $card['txt'];
                    $btn = $card['btn'];
                    $img_ico = $card['img_ico'];
                    $ico_type = $card['ico_type'];  
            ?>
            <div class="col-xs-12 col-md-6 col-lg-4 my-3 my-lg-0 animate__animated animate__delay-<?php echo $i-1;?>s"
                data-scroll data-scroll-class="animate__fadeInUp" style="--animate-delay:0.5s;">
                <div class="card border-0 h-100 shadow bg-white d-flex flex-column gap-5 px-5 py-7">

                    <?php if ( $ico_type !== 'No icona' ): ?>
                    <div class="card-icon">
                        <?php if ( $ico_type === 'Immagine' && !empty( $img_ico ) ): ?>
                        <img src="<?php echo esc_url( $img_ico['url'] ); ?>"
                            alt="<?php echo esc_attr( $img_ico['alt'] ); ?>" />
                        <?php elseif ( $ico_type === 'Numero' ): ?>
                        <div class="number rounded-circle d-flex align-items-center justify-content-center bg-light">
                            <p class="m-0"><?php echo $counter; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <div class="card-content">
                        <p><?php echo $title ?></p>
                        <p class="m-0"><?php echo $txt; ?></p>
                    </div>
                    <?php 
                    if( $btn ): 
                        $link_url = $btn['url'];
                        $link_title = $btn['title'];
                        $link_target = $btn['target'] ? $btn['target'] : '_self';
                        ?>
                    <a class="btn btn-primary btn-lg d-flex align-items-center text-nowrap"
                        href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
                        <?php echo esc_html( $link_title ); ?>
                        <i
                            class="icon-arrow-right bg-white text-dark ms-2 rounded p-2 fs-sm d-flex justify-content-center align-items-center"></i>
                    </a>
                    <?php endif; ?>
                </div>

            </div>
            <?php endif; ?>
            <?php 
            $counter++;
             endwhile; ?>
        </div>
        <?php endif; ?>
    </div>



</section>