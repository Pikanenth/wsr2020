<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dosislite
 */

?>
    </div><!-- #dosislite-primary -->
    <footer id="dosislite-footer">
        <?php if ( is_active_sidebar('footer-ins') ) : ?>
        <div class="footer-ins">
            <?php dynamic_sidebar('footer-ins'); ?>
        </div>
        <?php endif; ?>
       
        <div class="main-footer">
            <div class="container">
                <div class="logo-footer">
                    <?php if ( get_theme_mod('dosislite_logo_footer_url') ) { ?>
                    <img src="<?php echo esc_url( get_theme_mod('dosislite_logo_footer_url') ); ?>" alt="<?php bloginfo( 'name' ); ?>" />
                    <?php }else{ ?>
                    <h2 class="text-logo"><?php bloginfo('name'); ?></h2>
                    <?php } ?>
                    <span class="tagline"><?php echo get_bloginfo( 'description'); ?></span>
                </div>
                <div class="copyright">
                    <?php echo esc_html( get_theme_mod('dosislite_footer_copyright_text') ); ?>
                </div>
                <div class="footer-social dosislite-social">
                    <?php if(get_theme_mod('dosislite_facebook_url') ){ ?>
                    <a href="<?php echo esc_url( get_theme_mod('dosislite_facebook_url') ); ?>"><i class="fab fa-facebook-f"></i></a>
                    <?php } ?>
                    <?php if(get_theme_mod('dosislite_twitter_url') ){ ?>
                    <a href="<?php echo esc_url( get_theme_mod('dosislite_twitter_url') ); ?>"><i class="fab fa-twitter"></i></a>
                    <?php } ?>
                    <?php if(get_theme_mod('dosislite_pinterest_url') ){ ?>
                    <a href="<?php echo esc_url( get_theme_mod('dosislite_pinterest_url') ); ?>"><i class="fab fa-pinterest"></i></a>
                    <?php } ?>
                    <?php if(get_theme_mod('dosislite_instagram_url') ){ ?>
                    <a href="<?php echo esc_url( get_theme_mod('dosislite_instagram_url') ); ?>"><i class="fab fa-instagram"></i></a>
                    <?php } ?>
                    <?php if(get_theme_mod('dosislite_youtube_url') ){ ?>
                    <a href="<?php echo esc_url( get_theme_mod('dosislite_youtube_url') ); ?>"><i class="fab fa-youtube"></i></a>
                    <?php } ?> 
                    <?php if(get_theme_mod('dosislite_vimeo_url') ){ ?>
                    <a href="<?php echo esc_url( get_theme_mod('dosislite_vimeo_url') ); ?>"><i class="fab fa-vimeo-v"></i></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </footer>
</div>
    <?php wp_footer(); ?>
</body>
</html>