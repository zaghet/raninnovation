<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php do_action( 'tst_noscript' );?>
  <?php wp_body_open(); ?>
  <?php 
  if (!class_exists('ACF')) {
    return;
  }
  ?>
<div id="page" class="site">

  <header id="masthead" class="site-header <?php echo (get_field('home_viewport_height_full', 'options') && is_front_page()) ? "position-absolute w-100 z-3" : "";?>">

    <?php do_action( 'tst_before_header_top' );?>

    <?php do_action( 'tst_header_top' );?>      

    <?php do_action( 'tst_after_header_top' );?>
    
    <?php do_action( 'tst_before_header_bottom' );?>

    <?php do_action( 'tst_header_bottom' );?>

    <?php do_action( 'tst_after_header_bottom' );?>

  </header><!-- #masthead -->

  <div id="content" class="site-content position-relative pt-10">
    <?php do_action( 'tst_before_content' );?>