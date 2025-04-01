<?php
add_action('acf/init', function() {
    \TST\ACF::register_block('accordion', array(
        'title'             => __('Accordion'),
        'description'       => __('An accordion block.'),
    ));
});