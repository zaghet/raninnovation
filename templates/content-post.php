<?php
/**
 * Template part for displaying posts
 */
?>

<article id="post-<?php the_ID(); ?>" <?php // post_class('container'); ?>>
    
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="overflow-hidden" style="height: 15rem;">
            <?php the_post_thumbnail('full', ['class' => 'img-fluid w-100 object-fit-cover']); ?>
        </div>
    <?php endif; ?>

    <section>
        <div>
            <?php the_content(); ?>
        </div>
    </section>

</article>