<?php

/*
Plugin Name: Simple SEO
Plugin URI: http://coleds.com/wp-simple-seo
Description: A great plugin to modify the META information of your website, includes Google Analytics, Google Webmaster Tools, and more! Please <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=dave%40coleds%2ecom&item_name=Simple%20SEO%20&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank">Donate</a> if you find this plugin useful.
Version: 1.3.4
Author: David Cole
Author URI: http://coleds.com
License: GPL2
*/

/*
Copyright (C) 2017 Cole Design Studios, LLC, coleds.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/* No direct access. No, no, no... */
defined('ABSPATH') or die('Cheatin\' uh?');

define('CDS_SSEO_VERSION', '1.3.4');
define('CDS_SSEO_PATH', plugin_dir_path( __FILE__ ) );

require(CDS_SSEO_PATH.'/inc/class-cds-seo-form.php');


/*
 * Tell WP what to do when plugin is loaded
 *
 * @since 1.0
 */
function simpleseo_load() {
	register_setting('sseo-settings-group', 'sseo_default_meta_title');
	register_setting('sseo-settings-group', 'sseo_default_meta_description');
	register_setting('sseo-settings-group', 'sseo_default_meta_keywords');
	register_setting('sseo-settings-group', 'sseo_baindu_site_verification');
	register_setting('sseo-settings-group', 'sseo_bing_site_verification');
	register_setting('sseo-settings-group', 'sseo_gsite_verification');
	register_setting('sseo-settings-group', 'sseo_yandex_site_verification');
	register_setting('sseo-settings-group', 'sseo_ganalytics');
	register_setting('sseo-settings-group', 'sseo_robot_noindex');
	register_setting('sseo-settings-group', 'sseo_robot_nofollow');
		
	simpleseo_register();
}

add_action('admin_init', 'simpleseo_load');


/**
 * Registers JS and CSS.
 *
 * @since  1.0.0
 */
function simpleseo_register() {
	wp_register_style('sseo_style', plugins_url('css/style.css', __FILE__), false, CDS_SSEO_VERSION);
	wp_register_script('sseo_script', plugins_url('js/script.js', __FILE__), array('jquery'), CDS_SSEO_VERSION, true);
}


/**
 * Enqueues the CSS and JS files.
 *
 * @since  1.0.0
 */
function simpleseo_enqueue() {		
	wp_enqueue_style('sseo_style', plugins_url('css/style.css', __FILE__), false, CDS_SSEO_VERSION);
	wp_enqueue_script('sseo_script', plugins_url('js/script.js', __FILE__), array('jquery'), CDS_SSEO_VERSION, true);
}

add_action('admin_enqueue_scripts', 'simpleseo_enqueue');


/**
 * Description
 *
 * @since  1.0.0
 */
function simpleseo_meta_boxes() {
    add_meta_box( 
        'simple-seo',
        __('Simple SEO'),
        'simpleseo_show_meta_boxes'
    );
}


/**
 * Description
 *
 * @since  1.3.0
 */
function cds_sseo_description_limit($str, $n = 160) {
	if (strlen($str) < $n) {
		return $str;
	}

	$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

	if (strlen($str) <= $n) {
		return $str;
	}

	$out = "";
	foreach (explode(' ', trim($str)) as $val) {
		$out .= $val.' ';

		if (strlen($out) >= $n) {
			$out = trim($out);
			return (strlen($out) == strlen($str)) ? $out : $out;
		}       
	}
}


/**
 * Quick Edit
 *
 * @since  1.4.0
 */
if (!function_exists('sseo_change_page_columns')) {
	function sseo_change_page_columns($columns) {
		$columns['seo_title'] = __('SEO Title');
		$columns['seo_description'] = __('Meta Description');
		return $columns;
	}

	add_filter("manage_page_posts_columns", "sseo_change_page_columns");
}


/**
 * Quick Edit
 *
 * @since  1.4.0
 */
if (!function_exists('sseo_posttype_columns')) {
	function sseo_posttype_columns($column, $post_id) {
		switch($column) {
			case 'seo_title':
				$sseo_meta_title = get_post_meta($post_id, 'sseo_meta_title', true);
				echo '<input type="text" class="seo_title" value="'.$sseo_meta_title.'" name="seo_title" />';
				break;
			case 'seo_description':
				$sseo_meta_description = get_post_meta($post_id, 'sseo_meta_description', true);
				echo '<input type="text" class="seo_description" value="'.$sseo_meta_description.'" name="seo_description" />';
				break;
		}
	}
	add_action("manage_posts_custom_column", "sseo_posttype_columns", 10, 2);
}


/**
 * Quick Edit
 *
 * @since  1.4.0
 */
if (!function_exists('sseo_posttype_add_quick_edit')) {
	function sseo_posttype_add_quick_edit($column_name, $post_type) {
		if (!empty($_GET['post_type']) && $_GET['post_type'] != $post_type) {
			return;
		}
		
		static $printNonce = true;
		if ($printNonce) {
			$printNonce = false;
			wp_nonce_field(basename(__FILE__), 'sseo_nonce');
		}

		switch($column_name) { 
			case 'seo_title': ?>
		<fieldset class="inline-edit-col-left clear">
			<div class="inline-edit-col">
				<label>
					<span class="title">SEO Title</span>
					<span class="input-text-wrap">
						<input class="seo_title" type="text" name="sseo_meta_title" placeholder="">
						<span><span class="seo_title_count" style="color: rgb(112, 192, 52);">0</span> / 70 recommended characters</span></span>
				</label>
			</div>
		</fieldset>
		<?php break; case 'seo_description': ?>
		<fieldset class="inline-edit-col-left clear">
			<div class="inline-edit-col">
				<label>
					<span class="title">SEO Desc.</span>
					<span class="input-text-wrap">
						<textarea class="seo_description" name="sseo_meta_description"></textarea>
						<span><span class="seo_description_count" style="color: rgb(112, 192, 52);">0</span> / 350 recommended characters</span>
					</span>
				</label>
			</div>
		</fieldset>
		<?php break; }
	}
	
	add_action('quick_edit_custom_box', 'sseo_posttype_add_quick_edit', 10, 2);
}


/**
 * Quick Edit
 *
 * @since  1.4.0
 */
if (!function_exists('quickedit_enqueue_scripts') ) {
	function quickedit_enqueue_scripts($hook) {
		if ('edit.php' === $hook && isset($_GET['post_type']) && 'page' === $_GET['post_type']) {
			wp_enqueue_script('sseo_quickedit', plugins_url('js/quickedit.js', __FILE__), false, null, true);
		}
	}

	add_action('admin_enqueue_scripts', 'quickedit_enqueue_scripts');
}

/**
 * Quick Edit
 *
 * @since  1.4.0
 */
add_action('manage_page_posts_custom_column', 'sseo_custom_page_column', 10, 2);

function sseo_custom_page_column($column, $post_id) {
	switch ($column) {
    	case 'seo_title':
			echo get_post_meta($post_id, 'sseo_meta_title', true);
        	break;
    	case 'seo_description':
			echo get_post_meta($post_id, 'sseo_meta_description', true);
        	break;
    }
}

/**
 * Description
 *
 * @since  1.0.0
 */
function simpleseo_show_meta_boxes($post, $params) {
	global $wp;
	
    /* Use nonce for verification */
	wp_nonce_field(basename(__FILE__), 'sseo_nonce');

	$content .= '<div class="cds-seo-metabox-tabs">';
	$content .= '<input id="cds-seo-tab1" type="radio" name="tab-group" checked="checked" />';
	$content .= '<label class="tab" for="cds-seo-tab1" class="active">SEO</label>';
	$content .= '<input id="cds-seo-tab2" type="radio" name="tab-group" />';
	$content .= '<label class="tab" for="cds-seo-tab2">Keywords</label>';
	$content .= '<input id="cds-seo-tab3" type="radio" name="tab-group" />';
	$content .= '<label class="tab" for="cds-seo-tab3">Robots</label>';
	
	$content .= '<div id="cds-seo-preview" class="cds-seo-tab">';
	
	$sseo_meta_title = get_post_meta($post->ID, 'sseo_meta_title', true);
	$sseo_meta_description = get_post_meta($post->ID, 'sseo_meta_description', true);
	$sseo_meta_keywords = get_post_meta($post->ID, 'sseo_meta_keywords', true);
	$sseo_robot_noindex = get_post_meta($post->ID, 'sseo_robot_noindex', true);
	$sseo_robot_nofollow = get_post_meta($post->ID, 'sseo_robot_nofollow', true);
	$current_url = get_permalink($post->ID);
	
	if (empty($sseo_meta_description)) {
		$post_content = strip_tags($post->post_content);
		$preview_meta_description = cds_sseo_description_limit($post_content);
	} else {
		$preview_meta_description = $sseo_meta_description;
	}
	
	$content .= '<div class="cds-seo-section">';
	$content .= '<h3>Preview</h3>';
	$content .= '<div class="preview_snippet">';
	$content .= '<div id="sseo_snippet">';
	$content .= '<a><span id="sseo_snippet_title">'.$sseo_meta_title.'</span></a>';
	$content .= '<div class="cds-seo-current-url">';
	$content .= '<cite id="sseo_snippet_link">'.$current_url.'</cite>';
	$content .= '</div>'; /* cds-seo-current-url */
	$content .= '<span id="sseo_snippet_description">'.$preview_meta_description.'</span>';
	$content .= '</div>'; /* sseo_snippet */
	$content .= '</div>'; /* preview_snippet */
	$content .= '</div>'; /* cds-seo-section */

	$content .= '<div class="cds-seo-section">';
	$content .= '<h3>SEO</h3>';
	
	/* SSEO Input Fields */						
	$sseoForm = new cds_sseo_form_helper();
	$content .= $sseoForm->input('sseo_meta_title', array(
		'label' => 'Title',
		'value' => $sseo_meta_title,
	));

	$content .= '<p><span id="sseo_title_count">0</span> characters. Most search engines use a maximum of 60 chars for the title.</p>';

	$content .= $sseoForm->textarea('sseo_meta_description', array(
		'label' => 'Description',
		'value' => $sseo_meta_description,
	));

	$content .= '<p><span id="sseo_desc_count">0</span> characters. Most search engines use a maximum of 160 chars for the description.</p>';
	
	$content .= '</div>'; /* .cds-seo-section */
	$content .= '</div>'; /* #cds-seo-preview */
	
	$content .= '<div id="cds-seo-keywords" class="cds-seo-tab">';
	$content .= '<div class="cds-seo-section">';

	$content .= $sseoForm->textarea('sseo_meta_keywords', array(
		'label' => 'Keywords',
		'value' => $sseo_meta_keywords,
	));

	$content .= '<p>A comma separated list of your most important keywords for this page that will be written as META keywords.</p>';
	
	$content .= '</div>'; /* .cds-seo-section */
	$content .= '</div>'; /* cds-seo-keywords */
	
	$content .= '<div id="cds-seo-robots" class="cds-seo-tab">';
	$content .= '<div class="cds-seo-section">';
	$content .= '<h3>Robots</h3>';

	$content .= $sseoForm->input('sseo_robot_noindex', array(
		'type' => 'checkbox',
		'label' => 'Robots Meta NOINDEX',
		'checked' => $sseo_robot_noindex,
	));
	
	$content .= $sseoForm->input('sseo_robot_nofollow', array(
		'type' => 'checkbox',
		'label' => 'Robots Meta NOFOLLOW',
		'checked' => $sseo_robot_nofollow,
	));

	$content .= '</div>'; /* .cds-seo-section */
	$content .= '</div>'; /* #cds-seo-robots */
	$content .= '<div class="clearfix">&nbsp;</div>'; 
	$content .= '</div>'; /* .cds-seo-metabox-tabs */
	
	echo $content;
}

add_action('add_meta_boxes', 'simpleseo_meta_boxes');

/**
 * Description
 *
 * @since  1.0.0
 */
function simepleseo_admin_menu() {
	add_options_page('SEO Options', 'Simple SEO', 'manage_options', 'simepleseo_options', 'simepleseo_options');
}

add_action('admin_menu', 'simepleseo_admin_menu');

/**
 * Description
 *
 * @since  1.0.0
 */
function simepleseo_options() {
?>
<h1>Simple SEO Options</h1>

<div class="postbox-container" style="width:70%;">

	<form method="post" action="options.php" novalidate>

	<?php settings_fields('sseo-settings-group'); submit_button(); ?>
	
	<div class="metabox-holder">	
		<div class="meta-box-sortables ui-sortable" style="min-height: 0">
			<div id="simple_seo_buy" class="postbox">
				<h3 class="hndle ui-sortable-handle"><span>Home Page</span></h3>
				<div class="inside">
					<div class="main">
						<?php settings_fields('sseo-settings-group'); ?>
						<?php do_settings_sections('sseo-settings-group'); ?>

						<div id="sseo_data">
							<?php 

							$sseoForm = new cds_sseo_form_helper();
							echo $sseoForm->input('sseo_default_meta_title', array(
								'label' => 'Default Title',
								'value' => esc_attr(get_option('sseo_default_meta_title')),
							));

							echo '<p><span id="sseo_title_count">0</span> characters. Most search engines use a maximum of 60 chars for the title.</p>';

							echo $sseoForm->textarea('sseo_default_meta_description', array(
								'label' => 'Default Description',
								'value' => esc_attr(get_option('sseo_default_meta_description')),
							));

							echo '<p><span id="sseo_desc_count">0</span> characters. Most search engines use a maximum of 160 chars for the description.</p>';

							echo $sseoForm->textarea('sseo_default_meta_keywords', array(
								'label' => 'Default Keywords',
								'value' => esc_attr(get_option('sseo_default_meta_keywords')),
							));

							echo '<p>A comma separated list of your most important keywords for this page that will be written as META keywords.</p>';

							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="metabox-holder">	
		<div class="meta-box-sortables ui-sortable" style="min-height: 0">
			<div id="simple_seo_buy" class="postbox">
				<h3 class="hndle ui-sortable-handle"><span>Google</span></h3>
				<div class="inside">
					<div class="main">
						<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="sseo_gsite_verification">Google Webmaster Tools (<a href="https://support.google.com/webmasters/answer/35179?hl=en" target="_blank">Site Verification</a>)</label></p>
						<input name="sseo_gsite_verification" type="text" size="60" id="sseo_gsite_verification" value="<?php echo esc_attr(get_option('sseo_gsite_verification')); ?>">
						
						<p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="sseo_ganalytics">Google Analytics (<a href="https://support.google.com/analytics/answer/1008080?hl=en" target="_blank">Get Your Code</a>)</label></p>
						<input name="sseo_ganalytics" type="text" size="60" id="sseo_ganalytics" value="<?php echo esc_attr(get_option('sseo_ganalytics')); ?>">
						
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php submit_button(); ?>

	</form>
</div>

<div class="postbox-container" style="width:20%; margin-top: 35px; margin-left: 15px;">
	<div class="metabox-holder">
		
		<div class="meta-box-sortables ui-sortable" style="min-height: 0">
			<div id="cds_donate" class="postbox">
				<h3 class="hndle ui-sortable-handle"><span>Please Donate</span></h3>
				<div class="inside">
					<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=dave%40coleds%2ecom&item_name=Simple%20SEO%20&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8"><img src="<?php echo plugins_url('images/cds-seo-donate.png', __FILE__); ?>" width="100%" alt="Donate"></a></p>
				</div>
			</div>
		</div>
		
		<div class="meta-box-sortables ui-sortable" style="min-height: 0">
			<div id="cds_donate" class="postbox">
				<h3 class="hndle ui-sortable-handle"><span>Please Leave a Review!</span></h3>
				<div class="inside">
					<p><a href="https://wordpress.org/support/plugin/cds-simple-seo/reviews/#new-post"><img src="<?php echo plugins_url('images/leave-a-review-icon-blue.png', __FILE__); ?>" alt="Leave a Review!" width="100%"></a></p>
				</div>
			</div>
		</div>
		
		
			
		<div class="meta-box-sortables ui-sortable" style="min-height: 0">
			<div id="cds_facebook" class="postbox">
				<h3 class="hndle ui-sortable-handle"><span>Cole Design Studios on Facebook</span></h3>
				<div class="inside">
					<div style="float: left; margin-right: 5px"><img src="<?php echo plugins_url('images/facebooklogo.jpg', __FILE__); ?>" width="45" height="43" alt="Cole Design Studios, LLC, Facebook"></div>
					<p><a href="https://www.facebook.com/coledesignstudios">Check out the Cole Design Studios page on Facebook</a> for news and updates about your favourite plugin and more.</p>
				</div>
			</div>
		</div>

		<div class="meta-box-sortables ui-sortable" style="min-height: 0">
			<div id="simple_seo_help" class="postbox">
				<h3 class="hndle ui-sortable-handle"><span>Help and support</span></h3>
				<div class="inside">
					<p>For Simple SEO support:</p>
					<p>- Please email <a href="mailto:dave@coleds.com">David Cole</a> with any questions.</p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php }

/**
 * Description
 *
 * @since  1.0.0
 */
function simepleseo_save_postdata($post_id) {
	/* verify nonce */
	if (!wp_verify_nonce($_POST['sseo_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return $post_id;
	}

	/* check autosave */
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	/* Check permissions */
	if ($_POST['post_type'] == 'page') {
		if (!current_user_can('edit_page', $post_id)) {
        	return $post_id;
        }
	} elseif (!current_user_can('edit_post', $post_id)) {
    	return $post_id;
	}

	if (!wp_is_post_revision($post_id)) {
		$old_meta_title = get_post_meta($post_id, 'sseo_meta_title', true);
		$new_meta_title = null;
		if (isset($_POST['sseo_meta_title'])) {
			$new_meta_title = sanitize_text_field($_POST['sseo_meta_title']);
		}

		if ($new_meta_title && $new_meta_title != $old_meta_title) {
			update_post_meta($post_id, 'sseo_meta_title', $new_meta_title);
		} elseif (empty($new_meta_title) && $old_meta_title) {
			delete_post_meta($post_id, 'sseo_meta_title', $old_meta_title);
		}
		
		$old_meta_description = get_post_meta($post_id, 'sseo_meta_description', true);
		$new_meta_description = null;
		if (isset($_POST['sseo_meta_description'])) {
			$new_meta_description = sanitize_text_field($_POST['sseo_meta_description']);
		}
		
		if ($new_meta_description && $new_meta_description != $old_meta_description) {
			update_post_meta($post_id, 'sseo_meta_description', $new_meta_description);
		} elseif (empty($new_meta_description) && $old_meta_description) {
			delete_post_meta($post_id, 'sseo_meta_description', $old_meta_description);
		}
		
		$old_meta_keywords = get_post_meta($post_id, 'sseo_meta_keywords', true);
		$new_meta_keywords = null;
		if (isset($_POST['sseo_meta_keywords'])) {
			$new_meta_keywords = sanitize_text_field($_POST['sseo_meta_keywords']);
		}
		
		if ($new_meta_keywords && $new_meta_keywords != $old_meta_keywords) {
			update_post_meta($post_id, 'sseo_meta_keywords', $new_meta_keywords);
		} elseif (empty($new_meta_keywords) && $old_meta_keywords) {
			delete_post_meta($post_id, 'sseo_meta_keywords', $old_meta_keywords);
		}
		
		$old_sseo_gsite_verification = get_post_meta($post_id, 'sseo_gsite_verification', true);
		$new_sseo_gsite_verification = null;
		if (isset($_POST['sseo_gsite_verification'])) {
			$new_sseo_gsite_verification = sanitize_text_field($_POST['sseo_gsite_verification']);
		}
		
		if ($new_sseo_gsite_verification && $new_sseo_gsite_verification != $old_sseo_gsite_verification) {
			update_post_meta($post_id, 'sseo_gsite_verification', $new_sseo_gsite_verification);
		} elseif (empty($new_sseo_gsite_verification) && $old_sseo_gsite_verification) {
			delete_post_meta($post_id, 'sseo_gsite_verification', $old_sseo_gsite_verification);
		}
		
		$old_sseo_ganalytics = get_post_meta($post_id, 'sseo_ganalytics', true);
		$new_sseo_ganalytics = null;
		if (isset($_POST['sseo_ganalytics'])) {
			$new_sseo_ganalytics = sanitize_text_field($_POST['sseo_ganalytics']);
		}
		
		if ($new_sseo_ganalytics && $new_sseo_ganalytics != $old_sseo_ganalytics) {
			update_post_meta($post_id, 'sseo_ganalytics', $new_sseo_ganalytics);
		} elseif (empty($new_sseo_ganalytics) && $old_sseo_ganalytics) {
			delete_post_meta($post_id, 'sseo_ganalytics', $old_sseo_ganalytics);
		}
		
		if (!empty($_POST['sseo_robot_noindex'])) {
			update_post_meta($post_id, 'sseo_robot_noindex', $_POST['sseo_robot_noindex']);
		} else {
			delete_post_meta($post_id, 'sseo_robot_noindex');
		}
		
		if (!empty($_POST['sseo_robot_nofollow'])) {
			update_post_meta($post_id, 'sseo_robot_nofollow', $_POST['sseo_robot_nofollow']);
		} else {
			delete_post_meta($post_id, 'sseo_robot_nofollow');
		}
	}
}

add_action('save_post', 'simepleseo_save_postdata');

/**
 * Description
 *
 * @since  1.0.0
 */
function simpleseo_meta() {
	global $post;

	if (!is_object($post)) {
		return;
	}

	if (is_archive()) {
		return;
	}
	
	echo '<!-- This site is optimized with the Simple SEO plugin v'.CDS_SSEO_VERSION.' - https://wordpress.org/plugins/cds-simple-seo/ -->' . "\n";
		
	$keywords = null;
	if (is_front_page()) {
		$keywords = esc_attr(get_option('sseo_default_meta_keywords'));
		$keywords = apply_filters('sseo_default_meta_keywords', $keywords);
	}
	
	if (empty($keywords)) {
		$keywords = get_post_meta($post->ID, 'sseo_meta_keywords', true);
		$keywords = apply_filters('sseo_meta_keywords', $keywords);
	}
	
	if ($keywords) {
		echo '<meta name="keywords" content="'.$keywords.'" />' . "\n";
	}

	$description = null;
	if (is_front_page()) {
		$description = esc_attr(get_option('sseo_default_meta_description'));
		$description = apply_filters('sseo_default_meta_description', $description);		
	}
	
	if (empty($description)) {
		$description = get_post_meta($post->ID, 'sseo_meta_description', true);
		$description = apply_filters('sseo_meta_description', $description);
	}
	
	if ($description) {
		echo '<meta name="description" content="'.$description.'" />' . "\n";
	}
	
	$sseo_robot_noindex = get_post_meta($post->ID, 'sseo_robot_noindex', true);
	$sseo_robot_nofollow = get_post_meta($post->ID, 'sseo_robot_nofollow', true);
	
	if (!empty($sseo_robot_noindex) && !empty($sseo_robot_nofollow)) {
		echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
	} elseif (empty($sseo_robot_noindex) && !empty($sseo_robot_nofollow)) {
		echo '<meta name="robots" content="nofollow" />' . "\n";
	}
	if (!empty($sseo_robot_noindex) && empty($sseo_robot_nofollow)) {
		echo '<meta name="robots" content="noindex" />' . "\n";
	}
	
	echo '<!-- / Simple SEO plugin. -->' . "\n";
}

add_action('wp_head', 'simpleseo_meta');

/**
 * Description
 *
 * @since  1.0.0
 */
function simpleseo_title($title) {
	global $post;

	if (!is_object($post)) {
		return;
	}

	if (is_archive()) {
		return;
	}
	
	$meta_title = null;
	
	/* default */
	$default_title = esc_attr(get_option('sseo_default_meta_title'));
	$default_title = apply_filters('sseo_default_meta_title', $default_title);
	/* static page */
	$meta_title = get_post_meta($post->ID, 'sseo_meta_title', true);
	$meta_title = apply_filters('sseo_meta_title', $meta_title);
		
	if (is_front_page() && is_home()) {
		// Default homepage
		if (empty($meta_title)) {
			$meta_title = $default_title;
		}
	} elseif ( is_front_page() ) {
		// static homepage
		if (empty($meta_title)) {
			$meta_title = $default_title;
		}
	} elseif ( is_home() ) {
	  	// blog page
		if (empty($meta_title)) {
			$meta_title = $default_title;
		}
	}

	if (empty($meta_title)) {
		$site_name = get_bloginfo('name');
		return $post->post_title.' | '.$site_name;
	}
	
	return $meta_title;
}

add_filter('pre_get_document_title', 'simpleseo_title', 15);
add_filter('wp_title', 'simpleseo_title', 15);

/**
 * Adds Google analytics
 *
 * @since  1.0.2
 */
function simpleseo_analytics() {
	global $post;

	if (!is_object($post)) {
		return;
	}

	if (is_archive()) {
		return;
	}
	
	$version = CDS_SSEO_VERSION;
	$acode = esc_attr(get_option('sseo_ganalytics'));
	
	if(!empty($acode)) {
echo <<<END
<!-- This site uses the Google Analytics by Simple SEO plugin $version - https://wordpress.org/plugins/cds-simple-seo/ -->
<script type="text/javascript" src="https://www.google-analytics.com/analytics.js"></script>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', '$acode', 'auto');
ga('send', 'pageview');
</script>
<!-- / Google Analytics by Simple SEO -->

END;
	}
}

add_filter('wp_head', 'simpleseo_analytics', 22);

/**
 * Adds Google analytics
 *
 * @since  1.0.2
 */
function simpleseo_webmasterTools() {
	global $post;

	if (!is_object($post)) {
		return;
	}

	if (is_archive()) {
		return;
	}
	
	$version = CDS_SSEO_VERSION;
	$acode = esc_attr(get_option('sseo_gsite_verification'));

	if (!empty($acode)) {
echo <<<END
<!-- This site uses the Google Webmaster Tools by Simple SEO plugin $version - https://coleds.com/ -->
<meta name="google-site-verification" content="$acode" />
<!-- / Google Webmaster Tools by Simple SEO -->
END;
	}
}

add_filter('wp_head', 'simpleseo_webmasterTools', 22);

/**
 * Description
 *
 * @since  1.2.3
 */
function simpleseo_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=simepleseo_options">'.__('Settings').'</a>';
    array_push($links, $settings_link);
    $settings_link = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=dave%40coleds%2ecom&item_name=Simple%20SEO%20&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank">'.__('Donate').'</a>';
    array_push($links, $settings_link);
  	return $links;
}

$plugin = plugin_basename(__FILE__);

add_filter("plugin_action_links_$plugin", 'simpleseo_settings_link');

?>