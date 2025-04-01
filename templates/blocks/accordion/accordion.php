<?php
/**
 * Accordion Block Template.
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
if ( isset($block['anchor']) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'block block__accordion animate__animated py-7 py-lg-10';
if( isset($block['className']) ) {
    // $class_name .= ' ' . $block['className'];
}

if( isset($block['align']) ) {
    $class_name .= ' block-align-' . $block['align'];
}
if( isset($block['align_text']) ) {
    $class_name .= ' block-align_text-' . $block['align_text'];
}
if( isset($block['align_content']) ) {
    $class_name .= ' block-align_content-' . $block['align_content'];
}


if( $is_preview ) {
    $class_name .= ' is-admin';
}


$title = get_field('accordion_title');
$desc = get_field('accordion_desc');
$sections = get_field('accordion_sections');
$button = get_field('accordion_button');

?>

<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>" data-animation="animate__fadeIn">
    <div class="<?php echo $block['className']?>">
        <div class="row">
            <div class="col-xs-12 col-md-5 left">
                <?php if($title):?>
                <h2><?php echo $title; ?></h2>
                <?php endif; ?>
                <?php if($desc):?>
                <p><?php echo $desc; ?></p>
                <?php endif; ?>
                <?php if( !empty($button) && isset($button['url'], $button['title']) ): ?>
                    <a href="<?php echo esc_url($button['url']); ?>" 
                       class="btn btn-secondary animate__animated animate__delay-1s"
                       data-scroll data-scroll-class="animate__fadeInLeft" 
                       style="--animate-delay:0.8s"
                       <?php echo !empty($button['target']) ? 'target="' . esc_attr($button['target']) . '"' : ''; ?>>
                        <?php echo esc_html($button['title']); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="col-xs-12 col-md-7 right">
                <div class="accordion accordion-flush" id="accordion-<?php echo $block['id'];?>">
                    <?php if( $sections ): ?>
                        <?php foreach ($sections as $key => $section):?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                <button class="accordion-button <?php echo (!$key) ? '' : 'collapsed';?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $block['id'];?>-<?php echo $key;?>" <?php echo (!$key) ? 'aria-expanded="true"' : 'aria-expanded="false"';?>  aria-controls="collapse-<?php echo $block['id'];?>-<?php echo $key;?>">
                                    <?php echo $section['accordion_item_title']; ?>
                                </button>
                                </h2>
                                <div id="collapse-<?php echo $block['id'];?>-<?php echo $key;?>" class="accordion-collapse collapse <?php echo (!$key) ? 'show' : '';?>" data-bs-parent="#accordion-<?php echo $block['id'];?>">
                                <div class="accordion-body">
                                    <?php echo $section['accordion_item_desc']; ?>
                                </div>
                                <!-- <div class="accordion-link">
                                    <?php echo $section['accordion_item_link']; ?>
                                </div> -->
                                <?php if (!empty($section['accordion_item_link']) && isset($section['accordion_item_link']['url'], $section['accordion_item_link']['title'])): ?>
                                    <div class="accordion-link">
                                        <a href="<?php echo esc_url($section['accordion_item_link']['url']); ?>" 
                                        class="btn btn-outline-primary"
                                        <?php echo !empty($section['accordion_item_link']['target']) ? 'target="' . esc_attr($section['accordion_item_link']['target']) . '"' : ''; ?>>
                                            <?php echo esc_html($section['accordion_item_link']['title']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif;?>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container -->
</section>