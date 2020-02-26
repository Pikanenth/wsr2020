<?php 
    $class_blog = 'blog-2cols-grid';
    $post_class = 'col-md-6';
 ?>
<div class="dosislite-blog blog-grid <?php echo esc_attr( $class_blog ); ?>">
    <?php if ( have_posts() ) { ?>
    <div class="row">
        <?php while ( have_posts() ) { ?>
            <?php
                the_post();
            ?>
            <div <?php post_class($post_class); ?>>
                <div class="post-format">
                    <?php
                        $featured_image = get_template_directory_uri(). '/assets/images/place-holder.png';
                        if ( wp_get_attachment_url(get_post_thumbnail_id()) ) {
                            $featured_image = wp_get_attachment_url( get_post_thumbnail_id() );
                        }
                    ?>
                    <a href="<?php the_permalink() ?>" style="background-image: url('<?php echo esc_url($featured_image); ?>');"></a>
                </div>
                <div class="post-info">
                    <?php if ( get_the_title() == ''){ ?>
                        <div class="date-post">
                            <a href="<?php the_permalink(); ?>">
                                <span class="post-date"><?php echo get_the_date( 'd'); ?></span>
                                <span class="post-month"><?php echo get_the_date( 'M'); ?></span>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="date-post">
                            <span class="post-date"><?php echo get_the_date( 'd'); ?></span>
                            <span class="post-month"><?php echo get_the_date( 'M'); ?></span>
                        </div>
                    <?php } ?>
                    <div class="post-cats"><?php the_category(' ') ?></div>
                    <h3 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo the_title(); ?></a></h3>
                    <?php get_template_part('template-parts/post', 'meta'); ?>
                    <div class="post-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 25 , '...' ) ?></div>
                </div>        
            </div>
        <?php } ?>
    </div>
    <?php }else{ 
        get_template_part( 'template-parts/post', 'none' );
    } ?>
</div>
<?php dosislite_pagination(); ?>
