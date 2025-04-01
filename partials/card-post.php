<?php
/**
 * Template partial for displaying post in loop
 */
?>
<div class="col-xs-12 col-md-6 col-lg-4 my-3">
    <article id="post-<?php the_ID(); ?>" <?php post_class('card card--loop'); ?>>
        <?php 
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            echo '<span class="mt-4 mx-3 top-0 end-0 novita badge position-absolute text-bg-success">' . esc_html( $categories[0]->name ) . '</span>';
        }
        ?>
        <?php 
        if ( has_post_thumbnail() ) {
            echo '<div class="post-thumbnail">';
            echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
            the_post_thumbnail('card'); 
            echo '</a>';
            echo '</div>';
        }
        ?>
        <div class="card__content">
            <h5><?php the_title(); ?></h5>
            <?php 
            echo '<div class="post-excerpt">';
             echo wp_trim_words( get_the_excerpt(), 10, '...' );
            echo '</div>';
            ?>
            <a class="btn btn-primary btn-lg d-flex align-items-center text-nowrap" href="<?php the_permalink(); ?>">
                Scopri di più
                <i
                    class="icon-arrow-right bg-white text-dark ms-2 rounded p-2 fs-sm d-flex justify-content-center align-items-center"></i>

            </a>
        </div>
    </article>
</div>