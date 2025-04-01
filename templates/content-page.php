<?php
/**
 * Template part for displaying page content in page.php
 */

$id = get_the_ID();
?>
<article id="page-<?php echo $id; ?>" <?php post_class( ['blocks'] ); ?>>
	
<?php
while ( have_posts() ) :
	the_post();
	
	the_content();
endwhile; // End of the loop.
?>
	
</article><!-- #page-<?php the_ID(); ?> -->