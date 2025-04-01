<?php
/**
 * Template part for displaying posts
 */
?>

<article id="post-<?php the_ID(); ?>" <?php // post_class('container'); ?>>
    <section class="bg-light py-7 py-lg-10">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <?php the_title( '<h1 class="h2 m-0">', '</h1>' ); ?>
                </div>
            </div>
        </div>
    </section>
    <section class="py-7 py-lg-10">
        <div class="container px-lg-10">
            <?php the_content();?>
        </div>
    </section>

</article>