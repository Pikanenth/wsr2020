<?php
/*
Plugin Name: Custom Social Media Widget
Plugin URI: https://wordpress.org/plugins/custom-social-media-widget/
Description: Easily add social media widget in sidebar
Version: 1.0.0
Author: Vishit Shah
Author URI: https://www.linkedin.com/in/vishit-shah-5b393383/
License: GPLv2
*/

/* Plugin Licence

Copyright 2014 VISHIT SHAH (email : vishit99@gmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

// Make sure we don't expose any info if called directly
if ( basename( $_SERVER['PHP_SELF'] ) == basename( __FILE__ ) ) {
	die( 'Sorry, but you cannot access this page directly.' );
}
class custom_social_media_widget extends WP_Widget {

	function __construct() {
    parent::__construct(
            'custom_social_media_widget',
            __('Custom Social Networks Profiles', 'custom-social-media-widget'), // Name
            array('description' => __('Links to Author social media profile', 'custom-social-media-widget'),)
    );
}
public function form($instance) {
        isset($instance['title']) ? $title = $instance['title'] : null;
        empty($instance['title']) ? $title = 'My Social Profile' : null;
 
        $facebook = isset($instance['facebook']) ?  $instance['facebook'] : null;
        $twitter  = isset($instance['twitter']) ? $instance['twitter'] : null;
        $google   = isset($instance['google']) ?  $instance['google'] : null;
        $linkedin = isset($instance['linkedin']) ?  $instance['linkedin'] : null;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
 
        <p>
            <label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo esc_attr($facebook); ?>">
        </p>
 
        <p>
            <label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo esc_attr($twitter); ?>">
        </p>
 
        <p>
            <label for="<?php echo $this->get_field_id('google'); ?>"><?php _e('Google+:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('google'); ?>" name="<?php echo $this->get_field_name('google'); ?>" type="text" value="<?php echo esc_attr($google); ?>">
        </p>
 
        <p>
            <label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('Linkedin:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="text" value="<?php echo esc_attr($linkedin); ?>">
        </p>
 
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        $instance['facebook'] = (!empty($new_instance['facebook']) ) ? strip_tags($new_instance['facebook']) : '';
        $instance['twitter'] = (!empty($new_instance['twitter']) ) ? strip_tags($new_instance['twitter']) : '';
        $instance['google'] = (!empty($new_instance['google']) ) ? strip_tags($new_instance['google']) : '';
        $instance['linkedin'] = (!empty($new_instance['linkedin']) ) ? strip_tags($new_instance['linkedin']) : '';
 
        return $instance;
    }
    public function widget($args, $instance) {
 
        $title = apply_filters('widget_title', $instance['title']);
        $facebook = $instance['facebook'];
        $twitter = $instance['twitter'];
        $google = $instance['google'];
        $linkedin = $instance['linkedin'];
 
// social profile link
        $facebook_profile = '<a class="facebook" href="' . $facebook . '">F</a>';
        $twitter_profile = '<a class="twitter" href="' . $twitter . '">T</a>';
        $google_profile = '<a class="google" href="' . $google . '">G</a>';
        $linkedin_profile = '<a class="linkedin" href="' . $linkedin . '">L</a>';
 
echo $args['before_widget'];
if (!empty($title)) {
echo $args['before_title'] . $title . $args['after_title'];
}
 
        echo '<div class="social-icons">';
        echo (!empty($facebook) ) ? $facebook_profile : null;
        echo (!empty($twitter) ) ? $twitter_profile : null;
        echo (!empty($google) ) ? $google_profile : null;
        echo (!empty($linkedin) ) ? $linkedin_profile : null;
        echo '</div>';
        echo $args['after_widget'];
}

} // class Social_Widget

// register custom_social_media_widget widget
function register_socialmedia_widget() {
    register_widget('custom_social_media_widget');
}
 
add_action('widgets_init', 'register_socialmedia_widget');

// enqueue css stylesheet
        function smw_widget_css() {
        wp_enqueue_style('social-profile-widget', plugins_url('custom_social_media_widget.css', __FILE__));
}
        add_action('wp_enqueue_scripts', 'smw_widget_css');

