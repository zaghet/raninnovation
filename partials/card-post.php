<?php
/**
 * Template partial for displaying post in loop
 */
?>
<div class="col-xs-12 col-md-6 my-3">
    <article id="post-<?php the_ID(); ?>" <?php post_class('card card--loop'); ?>>
        <?php   
        if ( has_post_thumbnail() ) {
            echo '<div class="post-thumbnail">';
            echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
            the_post_thumbnail('card'); 
            echo '</a>';
            echo '</div>';
        }
        ?>
        <div class="card__content p-5">
            <h5><?php the_title(); ?></h5>
            <?php 
            echo '<div class="post-excerpt">';
             echo wp_trim_words( get_the_excerpt(), 10, '...' );
            echo '</div>';
            ?>
            <a class="btn btn-underline d-flex align-items-center text-nowrap" href="<?php the_permalink(); ?>">
                Leggi di più
            </a>
        </div>
    </article>
</div>