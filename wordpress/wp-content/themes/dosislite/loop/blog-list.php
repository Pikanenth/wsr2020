<div class="dosislite-blog blog-list">
    <?php if ( have_posts() ) { ?>
        <?php while ( have_posts() ) { ?>
            <?php
                the_post();
            ?>
            <div <?php post_class(); ?>>
               <div class="post-inner">
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="post-format">
                            <a href="<?php the_permalink() ?>" style="background-image: url('<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>');"></a>
                        </div>
                    <?php } ?>
                    <div class="post-info">
                        <div class="post-cats"><?php the_category(' ') ?></div>
                        <?php
                            $post_title = get_the_title() ? get_the_title() : get_the_date();
                        ?>
                        <h3 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo wp_kses_post( $post_title ); ?></a></h3>
                        <?php get_template_part('template-parts/post', 'meta'); ?>
                        <div class="post-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 30 , '...' ) ?></div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>
<?php dosislite_pagination(); ?>