<?php
add_action('acf/init', function() {
    \TST\ACF::register_block('banner-img-bg', array(
        'title'             => __('Banner Image Background'),
        'description'       => __('Banner with an Imgage in the Background'),
    ));
});