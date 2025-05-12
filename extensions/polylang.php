<?php
namespace TST;
if( ! class_exists( '\TST\POLYLANG' ) ) {
class POLYLANG {
    
    public function __construct(){
		
        $this->register_translations();

        // disable translation se polyang installato e disabilitato la languages dalle options
        add_action( 'template_redirect', [$this, 'disable_translation'] );

        add_action( 'tst_header_bottom', [$this, 'languages_switcher'], 15);
    }

    function disable_translation(){
        if ( !get_field( 'enable_languages', 'option') && 
            function_exists('pll_the_languages') )
        {
            $user = wp_get_current_user();
        
            if ( !$user->has_cap('administrator') && 
                pll_current_language('slug') !== pll_default_language( 'slug' ) )
            {
                wp_redirect( pll_home_url(pll_default_language( 'slug' )) );
                die;
            }
        }

    }

    function languages_switcher(){
        if ( function_exists( 'pll_the_languages' ) && get_field( 'enable_languages', 'option') && !TST_CONFIG['landing'] ) {
            $languages = pll_languages_list(
					[
						'hide_empty' => true,
						'fields' => 'slug'
					]
				);
            if(count($languages) <= 1){
                return;
            }
            $default = pll_default_language( 'slug' );
    	    $current = pll_current_language('slug');
            ?>
            <div class="language-switcher">
                <button class="w-100 btn btn-secondary btn-sm dropdown-toggle text-uppercase rounded-pill text-bg-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $current; ?>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach(pll_the_languages(array('raw' => 1)) as $language_code => $language_data): ?>
                        <?php if($language_data['current_lang'] == false): ?>
                            <li><a class="dropdown-item text-uppercase btn btn-secondary btn-sm rounded-pill text-bg-secondary" href="<?php echo $this->language_url($language_data); ?>"><?php echo strtoupper($language_code); ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php
        }
    }

    function language_url($language_data){   
        return $language_data['url'];
    }

    public function register_translations(){
        if(function_exists('pll_register_string')):
            // CARD ARTICLE
            pll_register_string("button card", "Scopri il servizio", "tst", false);
        endif;
    }

}
}
if (function_exists( 'pll_languages_list' ) && class_exists( '\TST\POLYLANG' )) {
	add_action('after_setup_theme', function(){
		new POLYLANG();	
	});
}