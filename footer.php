</div><!-- #content -->

<footer id="footer" class="site-footer">
    <div class="container py-5">
        <div class="row align-items-start">
            <!-- Colonna Logo e Info -->
            <div class="col-md-4 text-md-start text-center mb-4 mb-md-0">
                <?php $logo = get_field('logo_footer', 'option'); ?>
                <?php 
                if( !empty( $logo ) ): ?>
                    <img  class="mb-5" src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($logo['alt']); ?>" />
                <?php endif; ?>
                <?php $txt_footer = get_field('txt_footer', 'option', false, false);
                    if( !empty( $txt_footer) ) : ?>
                    <p class="fs-sm"><?php echo $txt_footer; ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Colonna Menu -->
            <div class="col-md-4 text-md-start text-center mb-4 mb-md-0">
            <?php  if(  has_nav_menu( 'menu-footer' )): ?>
                    <nav class="nav-footer">
                        <ul class="list-unstyled">
                            <?php
                                wp_nav_menu(array(
                                'theme_location' => 'menu-footer',
                                'container' => '',
                                'items_wrap' => '%3$s',
                                ));?>
                        </ul>
                    </nav>
                <?php endif;  ?>
            </div>
            
            <!-- Colonna Contatti -->
            <div class="col-md-4">
                <div class="">
                    <?php if( have_rows('social_footer', 'option') ): ?>
                        <div class="">
                            <?php while( have_rows('social_footer', 'option') ) : the_row();
                                $ico = get_sub_field('social_icon_footer', 'option');
                                $link = get_sub_field('social_link_footer', 'option'); ?>
                                
                                <div class="d-flex flex-column flex-md-row align-items-center gap-3 py-1">
                                    <?php if( !empty( $ico ) ): ?>
                                        <img src="<?php echo esc_url($ico['url']); ?>" alt="<?php echo esc_attr($ico['alt']); ?>" />
                                    <?php endif; ?>
                                    <?php if( $link ): 
                                        $link_url = $link['url'];
                                        $link_title = $link['title'];
                                        $link_target = $link['target'] ? $link['target'] : '_self';
                                        ?>
                                        <a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
                                            <?php echo esc_html( $link_title ); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>

                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    
    <!-- Copyright Section -->
    <div class="footer-bottom p-3" style="background-color: #f1b73b;">
        <div class="row">
            <div class="col-md-6 col-xs-12">
                <!-- <p class="m-0">&copy; 2024 Copyright - RAN Innovation</p> -->
                <?php $copyright = get_field('copyright_footer', 'option');
                if( !empty( $copyright ) ): ?>
                <p class="m-0 fs-xs text-center text-lg-start mb-3 mb-md-0"><?php  echo $copyright; ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-6 col-xs-12">
                <!-- <p class="m-0">&copy; Designed by TWOW</p> -->
                <?php $copyright = get_field('designed_by_footer', 'option');
                if( !empty( $copyright ) ): ?>
                <p class="m-0 fs-xs text-center text-lg-end mb-3 mb-md-0"><?php  echo $copyright; ?></p>
                <?php endif; ?> 
            </div>
        </div>
    </div>
</footer>

</div><!-- #page -->
<?php do_action( 'tst_after_page' );?>
<?php wp_footer(); ?>

</body>
</html>
