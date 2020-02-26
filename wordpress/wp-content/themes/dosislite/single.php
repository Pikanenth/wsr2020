<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Dosislite
 */

get_header(); ?>
<div class="main-contaier">
    <div class="container">
    <?php
        while ( have_posts() ) {
            the_post();
            $sticky_class = ( is_sticky() ) ? 'dosislite-post-sticky' : null;
            $featured_image = null;
            if ( has_post_thumbnail() ) {
                $featured_image = dosislite_resize_image( get_post_thumbnail_id(), null, 1530, 700, true, true );
            } ?>
        <div class="dosislite-single-post">
            <div <?php post_class("{$sticky_class} item-blog"); ?>>
                <div class="row justify-content-md-center">
                    <div class="col-sm-12 col-md-11 col-lg-10">
                        <div class="post-heading">
                            <div class="post-cats"><?php the_category(' ') ?></div>
                            <h1 class="post-title title-single"><?php the_title(); ?></h1>
                            <?php get_template_part('template-parts/post', 'meta'); ?>
                        </div>
                    </div>
                </div>                
                <?php if( $featured_image ) { ?>
                <div class="post-format">
                    <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr__('Featured Image', 'dosislite'); ?>" />
                </div>
                <?php } ?>
                <div class="post-info">
                    <div class="row justify-content-md-center">
                        <div class="col-sm-12 col-md-11 col-lg-10">
                            <div class="post-content">
                                <?php
                                    the_content();
                                    wp_link_pages(
                                        array(
                                            'before'   => '<p class="page-nav">' . esc_html__( 'Pages:', 'dosislite' ),
                                            'after'    => '</p>'
                                        )
                                    );
                                ?>
                            </div>
                            <?php if ( get_the_tags() ) { ?>
                            <div class="post-tags">
                                <?php the_tags(esc_html__('Tags: ', 'dosislite'), ', '); ?>
                            </div>
                            <?php } ?>
                            <?php get_template_part( 'template-parts/single', 'post-related' ); ?>
                             <?php
                                if ( comments_open() || get_comments_number() ) :
                                    comments_template('', true);
                                endif;
                            ?>                            
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    <?php } ?>
    
    </div>
</div>
<?php get_footer(); ?>
