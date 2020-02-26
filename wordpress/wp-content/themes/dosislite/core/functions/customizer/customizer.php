<?php
function dosislite_sanitize_checkbox( $checked ) {	
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

function dosislite_sanitize_number_absint( $number, $setting ) {
	// Ensure $number is an absolute integer (whole number, zero or greater).
	$number = absint( $number );
	
	// If the input is an absolute integer, return it; otherwise, return the default
	return ( $number ? $number : $setting->default );
}

function dosislite_sanitize_select( $input, $setting ) {
	
	// Ensure input is a slug.
	$input = sanitize_key( $input );
	
	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;
	
	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

if (class_exists('WP_Customize_Control')) {
    class WP_Customize_Category_Control extends WP_Customize_Control {
        /**
         * Render the control's content.
         *
         * @since 3.4.0
         */
        public function render_content() {
            $dropdown = wp_dropdown_categories(
                array(
                    'name'              => '_customize-dropdown-categories-' . $this->id,
                    'echo'              => 0,
                    'show_option_none'  => esc_html__( '&mdash; Select &mdash;', 'dosislite' ),
                    'option_none_value' => '0',
                    'selected'          => $this->value(),
                )
            );
 
            // Hackily add in the data link parameter.
            $dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );
 
            printf(
                '<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
                $this->label,
                $dropdown
            );
        }
    }

    class Customize_Number_Control extends WP_Customize_Control
    {
        public $type = 'number';
     
        public function render_content()
        {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <input type="number" name="quantity" <?php $this->link(); ?> value="<?php echo esc_textarea( $this->value() ); ?>" style="width: 70px;"/>
            </label>
            <?php
        }
    }
}

/**
 * Dosislite Theme Customizer
 *
 * @package Dosislite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function dosislite_customize_register( $wp_customize )
{
    

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';	

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'dosislite_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'dosislite_customize_partial_blogdescription',
		) );
	}
    
    $wp_customize->add_panel( 'dosislite-theme-options-panel', array(
        'priority' => 2,
        'capability' => 'edit_theme_options',
        'title' => esc_html__( 'Dosislite: Theme Options', 'dosislite' )
    ) );

    # Theme Options
    $wp_customize->add_panel('dosislite_panel', array('priority' => 1, 'capability'=> 'edit_theme_options', 'title' => esc_html__('Dosislite Theme Options', 'dosislite') ));
    
	# Sections
    $wp_customize->add_section( 'dosislite_section_social_media', array( 'title' => esc_html__('Social Media', 'dosislite'), 'panel' => 'dosislite_panel', 'priority' => 23 ) );
    $wp_customize->add_section( 'dosislite_section_footer', array('title' => esc_html__('Footer', 'dosislite'), 'panel' => 'dosislite_panel', 'priority' => 25 ));
    
    # Site Title - Tagline
    $wp_customize->add_setting('dosislite_hide_site_title', array('default' => false, 'sanitize_callback' => 'dosislite_sanitize_checkbox'));
    $wp_customize->add_setting('dosislite_hide_tagline', array('default' => false, 'sanitize_callback' => 'dosislite_sanitize_checkbox'));
    $wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dosislite_hide_site_title',
			array(
				'label' => esc_html__('Hide Site Title?', 'dosislite'),
				'section' => 'title_tagline',
                'type' => 'checkbox'
			)
		)
	);
	 $wp_customize->add_control(
		new WP_Customize_Control(
			$wp_customize,
			'dosislite_hide_tagline',
			array(
				'label' => esc_html__('Hide Tagline?', 'dosislite'),
				'section' => 'title_tagline',
                'type' => 'checkbox'
			)
		)
	);

    /** Social Media */
    $wp_customize->add_setting('dosislite_facebook_url', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_setting('dosislite_twitter_url', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_setting('dosislite_instagram_url', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_setting('dosislite_pinterest_url', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_setting('dosislite_youtube_url', array('default' => '', 'sanitize_callback' => 'sanitize_text_field')); 
    $wp_customize->add_setting('dosislite_vimeo_url', array('default' => '', 'sanitize_callback' => 'sanitize_text_field'));
    
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dosislite_facebook_url', array('label' => esc_html__('Facebook URL', 'dosislite'), 'section' => 'dosislite_section_social_media', 'settings' => 'dosislite_facebook_url', 'type' => 'text', 'priority' => 1)));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dosislite_twitter_url', array('label' => esc_html__('Twitter URL', 'dosislite'), 'section' => 'dosislite_section_social_media', 'settings' => 'dosislite_twitter_url', 'type' => 'text', 'priority' => 2)));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dosislite_instagram_url', array('label' => esc_html__('Instagram URL', 'dosislite'), 'section' => 'dosislite_section_social_media', 'settings' => 'dosislite_instagram_url', 'type' => 'text', 'priority' => 3)));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dosislite_pinterest_url', array('label' => esc_html__('Pinterest URL', 'dosislite'), 'section' => 'dosislite_section_social_media', 'settings' => 'dosislite_pinterest_url', 'type' => 'text', 'priority' => 4)));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dosislite_youtube_url', array('label' => esc_html__('Youtube URL', 'dosislite'), 'section' => 'dosislite_section_social_media', 'settings'  => 'dosislite_youtube_url', 'type' => 'text', 'priority' => 6)));
	$wp_customize->add_control(new WP_Customize_Control($wp_customize, 'dosislite_vimeo_url', array('label' => esc_html__('Vimeo URL', 'dosislite'), 'section' => 'dosislite_section_social_media', 'settings' => 'dosislite_vimeo_url', 'type' => 'text', 'priority' => 7)));

    /** Footer */

   	// Logo footer
    $wp_customize->add_setting( 'dosislite_logo_footer_url', array('default' => '', 'sanitize_callback' => 'sanitize_text_field' ));
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize, 'dosislite_logo_footer_url',
            array(
                'label' => esc_html__('Site Logo Footer', 'dosislite'),
                'section' => 'dosislite_section_footer'
            )
        )
    );
    //Copyright
    $wp_customize->add_setting( 'dosislite_footer_copyright_text', array( 'default' => esc_html__('Your Copyright Text Here', 'dosislite'), 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'footer_copyright', array('label' => esc_html__('Copyright Text', 'dosislite'), 'section' => 'dosislite_section_footer', 'settings' => 'dosislite_footer_copyright_text', 'type' => 'text')));
    
    /** Colors */
    $wp_customize->add_setting('dosislite_body_color', array('default' => esc_attr('#454545'), 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_setting('dosislite_accent_color', array('default' => esc_attr('#E66625'), 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dosislite_body_color', array('label' => esc_html__('Body Text Color', 'dosislite'), 'section' => 'colors', 'settings' => 'dosislite_body_color')));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dosislite_accent_color', array('label' => esc_html__('Accent Color', 'dosislite'), 'section' => 'colors', 'settings' => 'dosislite_accent_color')));
    
}
add_action( 'customize_register', 'dosislite_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function dosislite_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function dosislite_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function dosislite_customize_preview_js() {
	wp_enqueue_script( 'dosislite-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'dosislite_customize_preview_js' );
