<?php
/**
 * Offcanvas Card Block Template: lista di card di tipo post con apertura dettaglio in Offcanvas
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
if ( isset( $block['anchor'] ) ) {
    $anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
}

// Create class attribute allowing for custom "className" and "align" values.
$class_name = 'block block__offcanvas-card py-7 pb-0 pb-lg-0';
if( isset($block['className']) ) {
    // $class_name .= ' ' . $block['className'];
}
if( isset($block['align']) ) {
    $class_name .= ' block-align-' . $block['align'];
}
if( isset($block['align_text']) ) {
    $class_name .= ' block-align_text-' . $block['align'];
}
if( isset($block['align_content']) ) {
    $class_name .= ' block-align_content-' . $block['align'];
}


if( $is_preview ) {
    $class_name .= ' is-admin';
}


$title = get_field('oc_title');
$desc = get_field('oc_desc');
$theme = get_field('oc_theme_color') ? get_field('oc_theme_color') : 'primary';
$no_sidebar = get_field('no_sidebar');
// $image = get_field('_image');
$posts = get_field('oc_posts');
if( !$posts )
    return;

$offcanvas_output = [];
$offcanvas_ids = [];
?>
<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>" data-animation="animate__fadeIn">
    <?php if($title || $desc):?>
    <div class="container <?php echo $block['className']?>">
        <div class="row mb-6">
            <?php if($title):?>
            <div class="col-xs-12 col-lg-6">
                <h4><?php echo $title; ?></h4>
            </div>
            <?php endif; ?>
            <?php if($desc):?>
            <div class="col-xs-12 col-lg-6">
                <p class="m-0"><?php echo $txt; ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="container <?php echo $block['className']?>">
        <div class="row">
            <?php if( $posts ): ?>
            <?php foreach ($posts as $key => $post):
                $new_title = get_field('titolo_principio_attivo', $post->ID) ? get_field('titolo_principio_attivo', $post->ID) : $post->post_title;
                ?>
            <div class="col-12 col-md-6 mb-4">
                <div class="card p-4">
                    <div class="row g-0">
                        <div class="col-3">
                            <?php $url = get_the_post_thumbnail_url($post->ID, 'card-square' );//array('class' => 'h-100 w-100') ?>
                            <img src="<?php echo $url ?>" class="rounded"
                                alt="<?php echo $post->post_title ?>">
                        </div>
                        <div class="col-9 align-content-center">
                            <div class="card-body d-flex flex-column flex-lg-row align-content-center align-items-center">
                                <div>
                                    <h5 class="card-title"><?php echo $new_title ?></h5>
                                    <p class="card-text fs-sm mb-0"><?php echo $post->post_excerpt ?></p>
                                </div>
                                <?php if(empty($no_sidebar)): ?>
                                <button class="btn btn-<?php echo $theme;?> text-nowrap ms-lg-auto mt-3 mt-lg-0" type="button"
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvas-<?php echo $block['id'];?>-<?php echo $key;?>"
                                    aria-controls="offcanvas-<?php echo $block['id'];?>-<?php echo $key;?>">Più
                                    dettagli</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="offcanvas offcanvas-end" tabindex="-1"
                id="offcanvas-<?php echo $block['id'];?>-<?php echo $key;?>"
                aria-labelledby="offcanvas-<?php echo $block['id'];?>-<?php echo $key;?>-Label">
                <div class="offcanvas-header align-items-start">
                    <?php $url = get_the_post_thumbnail_url($post->ID, 'card-square' ); ?>
                    <img src="<?php echo $url ?>" class="rounded w-50" alt="<?php echo $new_title ?>">
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <h3 class="offcanvas-title" id="offcanvasExampleLabel"><?php echo $new_title ?></h3>
                    <p class="card-text fs-sm mb-3"><?php echo $post->post_excerpt ?></p>
                    <div>
                        <?php echo $post->post_content ?>
                    </div>
                </div>
            </div>
            <?php
            
            ?>

            <?php endforeach; ?>
            <?php endif;?>
        </div>
</section>
<script>
document.addEventListener("DOMContentLoaded", () => {
    var container = document.getElementById("<?php echo $block['anchor']?>");
    var wrapper = document.getElementById("content-after-page");

    var list_offcanvas = container.querySelectorAll(".offcanvas");

    if (wrapper && container && list_offcanvas) {
        list_offcanvas.forEach((node) => {
            wrapper.appendChild(node.cloneNode(true));
            node.remove();
        });
    }
});
</script>