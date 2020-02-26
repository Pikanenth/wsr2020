<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Dosislite
 */
get_header();    
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        $featured_image = null;
        if ( has_post_thumbnail() ) {
            $featured_image = dosislite_resize_image( get_post_thumbnail_id(), null, 1530, 700, true, true );
        } ?>
    <div class="main-contaier">
        <div class="container">
            <div class="page-content">
                <?php if ( $featured_image ) { ?>                        
                <div class="page-image">
                    <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr__('Featured Image', 'dosislite'); ?>"/>
                </div>
                <?php } ?>
                <div class="row justify-content-md-center">
                    <article <?php post_class('dosislite-page col-md-11 col-lg-10'); ?>>
                        <?php if ( get_the_title() ) : ?>
                            <h1 class="page-title"><?php the_title(); ?></h1>
                        <?php endif; ?>
                        <div class="page-excerpt">
                            <?php the_content(); ?>
                            <?php wp_link_pages(array('before'=>'<p class="page-nav">' . esc_html__( 'Pages:', 'dosislite' ), 'after' =>'</p>')); ?>
                        </div>
                        <?php comments_template( '', true );  ?>
                    </article>
                </div>
            </div>
        </div>
    </div><?php
    }
}
get_footer();