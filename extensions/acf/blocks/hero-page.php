<?php
add_action('acf/init', function() {
    \TST\ACF::register_block('hero-page', array(
        'title'             => __('Hero Page'),
        'description'       => __('The Hero Section for Internal Pages'),
    ));
});