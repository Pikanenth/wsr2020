<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Dosislite
 */
get_header();

	 $col = ( !is_active_sidebar('sidebar') ) ? 'no-sidebar col-md-12' : 'has-sidebar col-md-12 col-lg-8';
?>
<div class="main-contaier">
	<div class="container">
    	<?php
            if ( get_theme_mod('dosislite_featured_posts_enable') ) {
                get_template_part( 'template-parts/featured', 'posts-slider' );
            }
        ?>
        <div class="row wrapper-main-content">
	        <div class="<?php echo esc_attr($col); ?>">
	        	<div class="home-lastest-post">
	        		<?php get_template_part( 'loop/blog', 'grid' ); ?>
	        	</div>
	        </div>
	        <?php if ( is_active_sidebar('sidebar') ) { ?>
            <div class="col-md-12 col-lg-4">
				<?php get_sidebar(); ?>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php
get_footer();
