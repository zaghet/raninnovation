<?php echo '<!-- CUSTOM STORE LISTING LOADED -->'; ?>

<?php
global $wpsl_stores;
if ( ! empty( $wpsl_stores ) ) : ?>
    <ul class="wpsl-store-list">
        <?php foreach ( $wpsl_stores as $store ) : 
            $post_id = $store['id'];
        ?>
            <li class="wpsl-store" data-store-id="<?php echo esc_attr( $post_id ); ?>">
                <strong class="store-name"><?php echo esc_html( $store['name'] ); ?></strong><br>
                <?php echo esc_html( $store['address'] ); ?>, <?php echo esc_html( $store['city'] ); ?><br>

                <?php 
                $description = get_field( 'store_description', $post_id );
                if ( ! empty( $description ) ) : ?>
                    <p class="store-description" style="margin-top: 5px; font-size: 0.9em; color: #555;">
                        <?php echo nl2br( esc_html( $description ) ); ?>
                    </p>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p><?php esc_html_e( 'Nessun punto vendita trovato.', 'wp-store-locator' ); ?></p>
<?php endif; ?>
