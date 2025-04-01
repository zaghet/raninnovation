<?php
namespace TST;
if( ! class_exists( '\TST\Header' ) ) {
class Header extends Main {

    public function __construct(){

        // favicon
        add_action( 'wp_head', [$this, 'favicon'] );
        
        // noscript
        add_action( 'tst_noscript', [$this, 'noscript'] );

        // Before Top
        add_action( 'tst_before_header_top', [$this, 'banner'] );
        
        // Top

        // add_action( 'tst_header_top', [$this, 'top_start'], 0);
        // add_action( 'tst_header_bottom', [$this, 'links'], 5);
        // add_action( 'tst_header_top', [$this, 'social'], 10);
        // add_action( 'tst_header_top', [$this, 'top_end'], 100);
        
        // Bottom
        add_action( 'tst_header_bottom', [$this, 'bottom_start'], 0);
        add_action( 'tst_header_bottom', [$this, 'navbar'], 5);
        add_action( 'tst_header_bottom', [$this, 'bottom_end'], 100);

   
        // ACF bottom link
        add_action( 'tst_header_navbar_links', [$this, 'bottom_link'], 5);

        // Login/Account
        // add_action( 'tst_header_navbar_links', [$this, 'login'], 8);
        // Mini cart link
        // add_action( 'tst_header_navbar_links', [$this, 'mini_cart'], 10);
        // Test link
        // add_action( 'tst_header_navbar_links', [$this, 'test'], 9);

    }

    function noscript(){
        echo '<noscript>
  <style>
    /**
    * Reinstate scrolling for non-JS clients
    */
    .simplebar-content-wrapper {
      scrollbar-width: auto;
      -ms-overflow-style: auto;
    }

    .simplebar-content-wrapper::-webkit-scrollbar,
    .simplebar-hide-scrollbar::-webkit-scrollbar {
      display: initial;
      width: initial;
      height: initial;
    }
  </style>
</noscript>';
    }

    function favicon() {
        $favicon = get_field('logo_favicon', 'option');

        if($favicon){

            $img100 = aq_resize($favicon['url'],100,100);
            $img300 = aq_resize($favicon['url'],300,300);
            printf( "<link rel=\"icon\" href=\"%s\" sizes=\"32x32\" />\n", $img100 );
            printf( "<link rel=\"icon\" href=\"%s\" sizes=\"192x192\" />\n", $img300 );
            printf( "<link rel=\"apple-touch-icon\" href=\"%s\" />\n", $img300 );
        
        }
    }

    function banner() {
        if ( function_exists( 'pll_current_language' ) ) {
            $text = get_field('top_banner', $this->get_acf_option(pll_current_language( 'slug' )));
        }else{
            $text = get_field('top_banner', 'option');
        }
        if(!empty($text)){
            printf('<div class="banner-message bg-light fs-xs text-center text-dark py-2">%s</div>',$text);
        }
    }

    function bottom_link() {
        if (have_rows('link_bottom_header', 'option') && !TST_CONFIG['landing']):
            while (have_rows('link_bottom_header', 'option')) : the_row();
                $link = get_sub_field('link_bottom_header');
                $btn_color = get_sub_field('btn_bottom_header_color');

                // Classe btn
                $btn_color_class = $btn_color ? 'btn-primary' : 'btn-secondary';

                if ($link):
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                ?>
                    <li>
                        <a href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr( $link_target ); ?>" class="d-inline-flex btn <?php echo esc_attr($btn_color_class); ?> btn-lg">
                            <?php echo esc_html($link_title); ?>
                        </a>
                    </li>
                <?php 
                endif;
            endwhile;
        endif;
    }

    

    // function mini_cart() {
    //     if ( function_exists( 'dynamic_sidebar' ) ) {
    //         echo '<li>';
    //         dynamic_sidebar( 'mini-cart-sidebar' );
    //         echo '</li>';
    //     }
    // }

    function login() {
        if(is_user_logged_in()){
            $login_url = get_permalink( 1 );
            $link_title = '<i class="icon-user iconsize-5x"></i>';
            ?>
            <li>
            <a class="d-inline-flex btn btn-secondary btn-lg" href="<?php echo $login_url; ?>">
                <?php echo $link_title;?>
            </a>
            </li>
        <?php
        }else{
            // echo '<li>'.do_shortcode('[]').'</li>';
        }   
    }
 
    function top_start(){
        echo '<!-- top-header -->
              <div id="top_header" class="site-header__top fw-medium d-flex align-items-center">
                <div class="container d-flex align-items-center justify-content-end">';
    }

    function top_end(){
        echo '</div> <!-- container -->
        </div> <!-- top-header -->';
    }

    function bottom_start(){
        echo '<!-- bottom-header -->
              <div id="bottom_header" class="site-header__bottom d-flex align-items-center position-relative">';
    }

    function bottom_end(){
        echo '</div> <!-- bottom-header -->';
    }

    function navbar() {
        if (!class_exists('ACF') || TST_CONFIG['landing'])
            return;

        $logo = get_field('logo_header', 'option');

        get_template_part( 'partials/navbar', null, ['logo'=>$logo] );

    }

    function links(){
        if (!class_exists('ACF'))
            return;

        if (have_rows('link_header', 'option')):
                while (have_rows('link_header', 'option')) : the_row();
                $link = get_sub_field('link_header');

                if ($link):
                  $link_url = $link['url'];
                  $link_title = $link['title'];
                  $link_target = $link['target'] ? $link['target'] : '_self';
                ?>
                  <a class="me-3 text-secondary fw-bold" href="<?php echo esc_url($link_url); ?>" target="<?php echo esc_attr($link_target); ?>">
                    <?php echo esc_html($link_title); ?>
                  </a>
                <?php endif; ?>
                <?php
                endwhile;
              endif; 
    }

    function social(){
        if (have_rows('social_header', 'option')):
                while (have_rows('social_header', 'option')) : the_row();
                  $icon = get_sub_field('social_icon_header');
                  $link_social = get_sub_field('social_link_header');
                  $text_social = get_sub_field('social_text_header');
        ?>
            <?php
            if ($link_social): ?>
              <a href="<?php echo esc_url($link_social); ?>" target="_blank" class="d-flex align-items-center">

                <?php
                if (!empty($icon)): ?>
                  <img class="m-2" src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>" width="25" height="25" />
                <?php endif; ?>

                <p class="mb-0 text-secondary"><?php echo esc_html($text_social); ?></p>

              </a>
            <?php endif; ?>
        <?php
          endwhile;
        endif;

    }
}
if( ! function_exists( 'TST_HEADER_HELPER' ) ){
    function TST_HEADER_HELPER(){
        return new Header();
    }
}
TST_HEADER_HELPER();
}