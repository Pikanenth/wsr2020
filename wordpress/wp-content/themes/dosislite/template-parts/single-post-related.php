<?php 
$orig_post = $post;
global $post;
$categories = get_the_category($post->ID);
if ($categories) :
	$category_ids = array();
	foreach($categories as $individual_category) {
        $category_ids[] = $individual_category->term_id;
	}
	$args = array(
		'category__in'        => $category_ids,
		'post__not_in'        => array($post->ID),
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => 1,
		'orderby'             => 'rand'
	);
	$new_query = new WP_Query( $args );
?>
    <?php if( $new_query->have_posts() ) : ?>
    <div class="post-related dosislite-blog">
        <h3 class="post-related-title"><?php esc_html_e('Related Posts', 'dosislite'); ?></h3>
        <div class="row">
        <?php while( $new_query->have_posts() ) : $new_query->the_post(); ?>
            <div class="col-md-4 item-relate post">
                <div class="inner-post">
    				<?php if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) : ?>
                    <?php
                        $thumb = dosislite_resize_image( get_post_thumbnail_id() , wp_get_attachment_thumb_url(), 570, 524, true, true ); ?>
        				<div class="post-format post-image">
                            <figure><img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>"/></figure>
                        </div>
    				<?php endif; ?>
                    <div class="post-info">
                        <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php get_template_part('template-parts/post', 'meta'); ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
		</div> 
    </div>
    <?php endif; ?>
<?php endif;
$post = $orig_post;
wp_reset_query();