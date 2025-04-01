<?php
/**
 * The template for displaying home or archive.
 *
 * @package tst
 */

use TST\Content;

get_header(); 
Content::page_content();
get_footer();
?>