<?php
add_action('acf/init', function() {
    \TST\ACF::register_block('logo-text', array(
        'title'             => __('Logo and Text'),
        'description'       => __('A repeater of cards with logo, text and button.'),
        'icon' => '<svg width="16" height="16" viewBox="0 0 16 16"  xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-image">
<path d="M8 12.216L12.944 15.2L11.632 9.576L16 5.792L10.248 5.304L8 0L5.752 5.304L0 5.792L4.368 9.576L3.056 15.2L8 12.216Z" fill="currentColor"/>
</svg>',
        'keywords'          => array('logo-text', 'repeater', 'cards'),
    ));
});