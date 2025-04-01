<?php
/**
 * Template part for displaying home page
 */

?>

<article id="page-home" <?php post_class( ['blocks'] ); ?>>
<?php
while ( have_posts() ) :
	the_post();
	
	the_content();
endwhile; // End of the loop.
?>
</article><!-- #page-home -->