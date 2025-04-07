<?php
/**
 * Form Block Template.
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
$class_name = 'block__form';
if( !empty($block['className']) ) {
    $class_name .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $class_name .= ' block-align-' . $block['align'];
}
if( !empty($block['align_text']) ) {
    $class_name .= ' block-align_text-' . $block['align'];
}
if( !empty($block['align_content']) ) {
    $class_name .= ' block-align_content-' . $block['align'];
}


if( $is_preview ) {
    $class_name .= ' is-admin';
}


$title = get_field('form_title');
$text = get_field('form_text');
$form = get_field('form');
// $contacts = get_field('contacts');
// $email_link = $contacts['email'];
// $address_link = $contacts['indirizzo'];
// $form = get_field('form_title');

?>
<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
    <div class="container py-10">
        <div class="row">
            <div class="col-xs-12 col-md-7 col-lg-8 mb-5 mb-md-0">
                <h1 class="mb-5"><?php echo esc_html($title); ?></h1>

                <?php if (!empty($text)) : ?>
                    <div class="mb-5"><?php echo wp_kses_post($text); ?></div>
                <?php endif; ?>

                <div class="p-0">
                    <?php echo do_shortcode($form);?>
                </div>
            </div>
            <div class="col-xs-12 col-md-5 col-lg-4">
                <!-- <div class="bg-light p-4 p-sm-5 rounded">
                    <?php if ($email_link): ?>
                    <div class="border-bottom w-100 py-3">
                        <p class="fw-semibold">Email:</p>
                        <div class="d-flex align-items-center gap-2">
                            <i class="icon-message text-dark d-flex justify-content-center align-items-center"></i>
                            <a href="<?php echo esc_url($email_link['url']); ?>"
                                target="<?php echo esc_attr($email_link['target']); ?>">
                                <?php echo esc_html($email_link['title']); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if ($address_link): ?>
                    <div class="border-bottom w-100 py-3">
                        <p class="fw-semibold">Indirizzo:</p>
                        <div class="d-flex align-items-center gap-2">
                            <i class="icon-map text-dark d-flex justify-content-center align-items-center"></i>
                            <a href="<?php echo esc_url($address_link['url']); ?>"
                                target="<?php echo esc_attr($address_link['target']); ?>">
                                <?php echo esc_html($address_link['title']); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div> -->
            </div>
        </div>
    </div>
</section>