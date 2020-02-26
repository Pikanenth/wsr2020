<?php
/**
 * Dosislite functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Dosislite
 */

# Define
define('DOSISLITE_LIBS_URI', get_template_directory_uri() . '/libs/');
define('DOSISLITE_CORE_PATH', get_template_directory() . '/core/');
define('DOSISLITE_CORE_URI', get_template_directory_uri() . '/core/');
define('DOSISLITE_CORE_CLASSES', DOSISLITE_CORE_PATH . 'classes/');
define('DOSISLITE_CORE_FUNCTIONS', DOSISLITE_CORE_PATH . 'functions/');
define('DOSISLITE_CORE_WIDGETS', DOSISLITE_CORE_PATH . 'widgets/');

if ( ! function_exists( 'dosislite_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function dosislite_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Dosislite, use a find and replace
		 * to change 'dosislite' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'dosislite', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
            array(
                'primary' => esc_html__('Main Menu', 'dosislite')
            )
        );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'dosislite_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 300,
			'width'       => 500,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'dosislite_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function dosislite_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'dosislite_content_width', 640 );
}
add_action( 'after_setup_theme', 'dosislite_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function dosislite_widgets_init() {
	register_sidebar(array(
		'name'            => esc_html__('Sidebar', 'dosislite'),
		'id'              => 'sidebar',
		'description'	  => 'The widget will be displayed in the sidebar on the blog page',
		'before_widget'   => '<div id="%1$s" class="widget %2$s">',
		'after_widget'    => '</div>',
		'before_title'    => '<h4 class="widget-title">',
		'after_title'     => '</h4>'
	));
    register_sidebar(array(
		'name'            => esc_html__('Footer Instagram', 'dosislite'),
		'id'              => 'footer-ins',
		'description'	  => 'The Instagram widget will display above the footer',
		'before_widget'   => '<div id="%1$s" class="widget %2$s">',
		'after_widget'    => '</div>',
		'before_title'    => '<h4 class="widget-title">',
		'after_title'     => '</h4>'
	));
    register_sidebar(array(
		'name'            => esc_html__('Nav Sidebar', 'dosislite'),
		'id'              => 'nav-sidebar',
		'description'     => 'Nav Sidebar is the place to display the featured widgets for your blog',
		'before_widget'   => '<div id="%1$s" class="widget %2$s">',
		'after_widget'    => '</div>',
		'before_title'    => '<h4 class="widget-title">',
		'after_title'     => '</h4>'
	));
}
add_action( 'widgets_init', 'dosislite_widgets_init' );

# Google Fonts
add_action( 'wp_enqueue_scripts', 'dosislite_enqueue_googlefonts' );
function dosislite_enqueue_googlefonts()
{
    $fonts_url = '';
    $Oswald = _x( 'on', 'Oswald: on or off', 'dosislite' );
    $Open_Sans = _x( 'on', 'Work Sans font: on or off', 'dosislite' );
    if( 'off' != $Oswald || 'off' != $Open_Sans )
    {
        $font_families = array();
        if ( 'off' !== $Oswald ) $font_families[] = 'Oswald:400,500';
        if ( 'off' !== $Open_Sans ) $font_families[] = 'Work Sans:400';
        $query_args = array('family' => urlencode(implode('|', $font_families)), 'subset' => urlencode('latin,latin-ext'));
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }

    wp_enqueue_style('dosislite-googlefonts', esc_url_raw($fonts_url), array(), null);
}

add_action( 'enqueue_block_editor_assets', 'dosislite_enqueue_googlefonts' );

/**
 * Enqueue scripts and styles.
 */
function dosislite_scripts() {
     # CSS
    wp_enqueue_style('bootstrap', DOSISLITE_LIBS_URI . 'bootstrap/bootstrap.min.css');
    wp_enqueue_style('font-awesome', DOSISLITE_LIBS_URI . 'font-awesome/css/all.min.css');
    wp_enqueue_style('chosen', DOSISLITE_LIBS_URI . 'chosen/chosen.css');
    wp_enqueue_style('owl-carousel', DOSISLITE_LIBS_URI . 'owl/owl.carousel.min.css');
    wp_enqueue_style( 'dosislite-style', get_stylesheet_uri() );

    # JS
	wp_enqueue_script('fitvids', DOSISLITE_LIBS_URI . 'fitvids/fitvids.js', array('jquery'), false, true);
    wp_enqueue_script('owl-carousel', DOSISLITE_LIBS_URI . 'owl/owl.carousel.min.js', array(), false, true);
    wp_enqueue_script('chosen', DOSISLITE_LIBS_URI . 'chosen/chosen.js', array(), false, true);
    wp_enqueue_script('imagesloaded');
    wp_enqueue_script('dosislite-scripts', get_template_directory_uri() . '/assets/js/dosislite-scripts.js', array(), false, true);
    
    if ( is_singular() && comments_open() && get_option('thread_comments') ) {
        wp_enqueue_script('comment-reply');
    }
}
add_action( 'wp_enqueue_scripts', 'dosislite_scripts' );


# Check file exists
function dosislite_require_file( $path ) {
    if ( file_exists($path) ) {
        require $path;
    }
}

# Require file
dosislite_require_file( get_template_directory() . '/core/init.php' );


# Comment Layout
function dosislite_custom_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo esc_attr($tag); ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
		<div class="comment-author">
		<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		</div>
		<div class="comment-content">
		    <h4 class="author-name"><?php echo get_comment_author_link(); ?></h4>
			<div class="date-comment">
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php echo get_comment_date() . esc_html__( 'at', 'dosislite' ) . get_comment_time(); ?>
				</a>
			</div>
			<div class="reply">
				<?php edit_comment_link( esc_html__( '(Edit)', 'dosislite' ), '  ', '' );?>
				<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'dosislite' ); ?></em>
				<br />
			<?php endif; ?>
			<div class="comment-text"><?php comment_text(); ?></div>
		</div>	
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}

# Pagination
function dosislite_pagination()
{
    global $wp_query;
    if ( (int)$wp_query->found_posts > (int)get_option('posts_per_page') ) : ?>
    <div class="dosislite-pagination"><?php
        $args = array(
            'prev_text' => '<span class="fa fa-angle-left"></span>',
            'next_text' => '<span class="fa fa-angle-right"></span>'
        );
        the_posts_pagination($args);
    ?>
    </div>
    <?php
    endif;
}

# Include the TGM_Plugin_Activation class
add_action('tgmpa_register', 'dosislite_register_required_plugins');
function dosislite_register_required_plugins()
{
	$plugins = array(
        array(
			'name'   => esc_html__('WP Instagram Widget', 'dosislite'),
			'slug'   => 'wp-instagram-widget'
		),
		array(
			'name'   => esc_html__('Contact Form 7', 'dosislite'),
			'slug'   => 'contact-form-7'
		),
        array(
			'name'   => esc_html__('MailChimp for WordPress', 'dosislite'),
			'slug'   => 'mailchimp-for-wp'
		),
        array(
            'name'  => esc_html__('Shared Counts Social Media Share Buttons', 'dosislite'),
            'slug'  => 'shared-counts'
        ),
	);

	$config = array(
		'id'           => 'dosislite',
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => true,
		'dismiss_msg'  => '',
		'is_automatic' => true,
		'message'      => ''
	);
	tgmpa($plugins, $config);
}

function dosilite_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'dosilite_skip_link_focus_fix' );