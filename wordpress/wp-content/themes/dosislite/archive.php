<?php
/**
 * The template for displaying archive pages
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
        <div class="archive-box main-blog">
            <?php the_archive_title('<h1>','</h1>'); ?>
        </div>    
        <div class="row wrapper-main-content">
            <div class="<?php echo esc_attr( $col ); ?>">
            <?php
             get_template_part( 'loop/blog', 'grid' );
            ?>
            </div>
            <?php if ( is_active_sidebar('sidebar') ) { ?>
            <div class="col-md-12 col-lg-4">
                <aside id="sidebar" class="sidebar">
                    <?php dynamic_sidebar('sidebar'); ?>
                </aside>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>