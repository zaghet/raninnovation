<?php
    // Params from ACF
    $logo = $args['logo'];
?>
<nav class="navbar navbar-expand-lg w-100">

    <div class="container">

        <?php if (!empty($logo)): ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar-brand me-2">
                <img class="header" src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>" />
            </a>
        <?php endif; ?>

        <ul class="nav nav-bottom text-end d-inline-flex flex-nowrap order-0 order-lg-2 ms-auto ms-lg-0 me-3 me-lg-0">
            <?php do_action( 'tst_header_navbar_links' ); ?>
        </ul>
        <!-- Hamburger -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsTST" aria-controls="navbarsTST" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end text-center text-lg-start" id="navbarsTST">

            <!-- MENU DESKTOP -->
            <ul class="navbar-nav me-2 mb-2 mb-lg-0 text-start fs-md d-none d-lg-inline-flex ">
                <?php
                if(  has_nav_menu( 'menu-main' ) && class_exists( '\TST\Navwalker' ) ):
                    
                wp_nav_menu(array(
                    'theme_location' => 'menu-main',
                    'depth' => 4,
                    'container' => '',
                    'items_wrap' => '%3$s',
                    'walker' => new \TST\Navwalker()
                ));
                
                endif;
                ?>
            </ul><!-- MENU DESKTOP -->
        
            <!-- MENU MOBILE -->
            <div class="d-block d-lg-none wrapper-menu-mobile">
                
                    <ul class="navbar-nav mb-5 text-start"> 
                    <?php
                        if(  has_nav_menu( 'menu-main' ) && class_exists( '\TST\Navwalker_Mobile' ) ):
                            wp_nav_menu(array(
                                'theme_location' => 'menu-main',
                                'depth' => 4,
                                'container' => '',
                                'items_wrap' => '%3$s',
                                'walker' => new \TST\Navwalker_Mobile()
                            ));
                        endif;
                    ?>
                    </ul>
                    
               
            </div><!-- MENU MOBILE -->

            
        </div><!-- /.collapse -->
        

    </div><!-- /.container -->
</nav><!-- /.navbar -->