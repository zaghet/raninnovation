<?php
/**
 * Template partial for displaying post in loop
 */
$colore_sfondo = get_post_meta(get_the_ID(), '_sfondo_colore', true);
if (!$colore_sfondo) {
    $colore_sfondo = '#ffffff'; // Colore di sfondo di default (bianco)
}

// Recupera il colore del testo (black o white)
$colore_testo = get_post_meta(get_the_ID(), '_colore_testo', true);
if (!$colore_testo) {
    $colore_testo = 'black'; // Colore del testo di default (nero)
}
?>

<div class="col-xs-12 col-md-6 col-lg-4 m-0 p-0">
    <article id="post-<?php the_ID(); ?>" <?php post_class('card card--loop custom-post-card text-' . esc_attr($colore_testo)); ?> style="background-color: <?php echo esc_attr($colore_sfondo); ?>; border:0;">
        <?php 
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            echo '<span class="mt-4 mx-3 top-0 end-0 novita badge position-absolute text-bg-success">' . esc_html( $categories[0]->name ) . '</span>';
        }
        ?>
        <div class="card__content">
            <?php 
            if ( has_post_thumbnail() ) {
                echo '<div class="post-thumbnail">';
                echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">';
                $thumbnail = get_the_post_thumbnail(null, 'card', ['style' => 'width: 20%; height: auto;']);
                echo $thumbnail;
                echo '</a>';
                echo '</div>';
            }
            ?>
            <h5><?php the_title(); ?></h5>
            <?php 
            echo '<div class="post-excerpt">';
            echo wp_trim_words( get_the_excerpt(), 100, '...' );
            echo '</div>';
            ?>
            <a class="btn btn-primary btn-lg d-flex align-items-center text-nowrap" href="<?php the_permalink(); ?>">
                <?php pll_e('Scopri il servizio') ?>
            </a>
        </div>
    </article>
</div>