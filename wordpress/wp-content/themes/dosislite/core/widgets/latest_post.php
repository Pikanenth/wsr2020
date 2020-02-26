<?php
class Dosislite_Widget_Latest_Posts_Widget extends WP_Widget
{
	function __construct()
    {
		$widget_ops = array( 'classname' => 'dosislite_latest_posts_widget', 'description' => esc_html__('A widget that displays your latest posts from all categories or a certain', 'dosislite') );
		parent::__construct( 'dosislite_latest_posts_widget', esc_html__('DOSISLITE: Sidebar Latest Posts', 'dosislite'), $widget_ops );
	}

	function widget( $args, $instance )
    {
		extract( $args );
		$title        = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : esc_html__('Latest Posts', 'dosislite') );
		$title_limited= isset($instance['title_limited']) ? $instance['title_limited'] : null;
		$categories   = isset($instance['categories']) ? $instance['categories'] : null;
		$style   	= isset($instance['style']) ? $instance['style'] : 'list';
		$number       = isset($instance['number']) ? $instance['number'] : 5;
		$query        = array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1, 'category_name' => $categories);
        if ( $categories == 'all' ) {
            $query = array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1);
        }
        
		$loop         = new WP_Query($query);
	        
        if ( $loop->have_posts() ) :
        	echo wp_kses_post($before_widget);
    		if ( $title ) {
    		    echo wp_kses_post($before_title . $title . $after_title);
    		}?>

			<div class="dosislite-post-widget <?php echo esc_attr( $style ); ?>">			
		        <?php if ( $style == 'list' ) { ?>					
		        	<div class="list-post-thumb">
					<?php
			        while ( $loop->have_posts() ) { $loop->the_post(); 
			        	$featured_image = dosislite_resize_image( get_post_thumbnail_id(), null, 585, 390, true, false );
			        	?>
			            <div class="post-item">
			             	<?php if ( has_post_thumbnail() ) { ?>
						    <div class="post-format">
						        <figure><img src="<?php echo esc_url( $featured_image ); ?>" alt="<?php the_title_attribute(); ?>"/></figure>
						    </div>
						    <?php } ?>
						    <div class="post-info">
						        <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						        <div class="date-post">
							        <?php if ( get_the_date() ) { ?>
							        <span class="post-date"><?php echo get_the_date(); ?></span>
							        <?php } ?>
							    </div>
						    </div>
			            </div>
			    	<?php } ?>
			    	</div>
				<?php } else { ?>
					<?php 
		        		$j = 0;
		        		$count = $loop->post_count;
			        	while ( $loop->have_posts() ) { $loop->the_post();
			        		$j++; 
			        		$featured_image = dosislite_resize_image( get_post_thumbnail_id(), null, 585, 390, true, false );
			        		$featured_image_big = dosislite_resize_image( get_post_thumbnail_id(), null, 690, 460, true, false );
			        		$title = (int)$title_limited > 0 ? substr( get_the_title(), 0, $title_limited) . '...' : get_the_title();
			        		?>
			        		<?php if ( $j == 1 ) { ?>
			        		<div class="post-item item-post-first">
		        				<?php if ( has_post_thumbnail() ) { ?>
							    <div class="post-format">
							        <figure><img src="<?php echo esc_url( $featured_image_big ); ?>" alt="<?php the_title_attribute(); ?>"/></figure>
							    </div>
							    <?php } ?>
							    <div class="post-info">
							        <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							    </div>
			        		</div>
			        		<div class="grid-post-thumb">
			        			<div class="row">
			        		<?php } else {?>
			        		 	<div <?php post_class('col-sm-6 post-item'); ?>>
			        		 		<?php if ( has_post_thumbnail() ) { ?>
								    <div class="post-format">
								        <figure><img src="<?php echo esc_url( $featured_image ); ?>" alt="<?php the_title_attribute(); ?>"/></figure>
								    </div>
								    <?php } ?>
								    <div class="post-info">
								        <h3 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo esc_html($title); ?></a></h3>
								    </div>
			        		 	</div>
			        		 <?php } ?>
							<?php if($j == $count) {?>
								</div>
			        		 </div>
			        		<?php } ?>
		        	<?php } ?>
		        <?php } ?>
	        </div>
			<?php
	        wp_reset_postdata();
            echo wp_kses_post($after_widget);

    	endif;
	}

	function update( $new_instance, $old_instance )
    {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['title_limited'] = strip_tags( $new_instance['title_limited'] );
		$instance['categories'] = sanitize_text_field($new_instance['categories']);
		$instance['style'] = sanitize_text_field($new_instance['style']);
		$instance['number'] = sanitize_text_field( $new_instance['number'] );
		return $instance;
	}

	function form( $instance )
    {
		$defaults = array( 'title' => esc_html__('Latest Posts', 'dosislite'), 'number' => 5, 'categories' => '', 'title_limited' => '', 'style' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title:', 'dosislite'); ?></label>
			<input  type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr($instance['title']); ?>"  />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id('title_limited') ); ?>"><?php esc_html_e('Limit the charactors for the title:', 'dosislite'); ?></label>
			<input  type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title_limited') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title_limited') ); ?>" value="<?php echo esc_attr($instance['title_limited']); ?>"  />
		</p>
        <p>
			<label for="<?php echo esc_attr( $this->get_field_id('style') ); ?>"><?php esc_html_e('Style:', 'dosislite'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>" class="widefat" style="width:100%;">
    			<option value='list' <?php if ('list' == $instance['style']) echo 'selected="selected"'; ?>><?php esc_html_e('List', 'dosislite'); ?></option>
    			<option value='grid' <?php if ('grid' == $instance['style']) echo 'selected="selected"'; ?>><?php esc_html_e('grid', 'dosislite'); ?></option>
    		</select>
		</p>
		<p>
    		<label for="<?php echo esc_attr($this->get_field_id('categories')); ?>"><?php esc_html_e('Filter by Category:', 'dosislite'); ?></label> 
    		<select id="<?php echo esc_attr($this->get_field_id('categories')); ?>" name="<?php echo esc_attr($this->get_field_name('categories')); ?>" class="widefat categories" style="width:100%;">
    			<option value='all' <?php if ('all' == $instance['categories']) echo 'selected="selected"'; ?>><?php esc_html_e('All categories', 'dosislite'); ?></option>
    			<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
    			<?php foreach($categories as $category) { ?>
    			<option value='<?php echo esc_attr($category->slug); ?>' <?php if ($category->slug == $instance['categories']) echo esc_attr('selected="selected"'); ?>><?php echo esc_html($category->cat_name); ?></option>
    			<?php } ?>
    		</select>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e('Number of posts to show:', 'dosislite'); ?></label>
			<input  type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" value="<?php echo esc_attr($instance['number']); ?>" size="3" />
		</p>
	<?php
	}
}

add_action( 'widgets_init', 'dosislite_latest_posts_init' );
function dosislite_latest_posts_init() {
	register_widget( 'Dosislite_Widget_Latest_Posts_Widget' );
}
