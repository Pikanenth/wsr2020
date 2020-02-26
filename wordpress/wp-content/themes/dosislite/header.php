<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dosislite
 */

?>
<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <a class="skip-link screen-reader-text" href="#content">
    <?php esc_html_e( 'Skip to content', 'dosislite' ); ?></a>
    <div class="body-overlay"></div>
    <?php if ( is_active_sidebar('nav-sidebar') ) { ?>
    <div class="dosislite-navsidebar nav-siderbar">
        <div class="logo-navbar">
            <?php
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
            if ( has_custom_logo() && isset($logo[0]) ) { ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo get_bloginfo('name'); ?>"></a>
            <?php
            } else { ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo('name'); ?> </a>
                <span class="tagline"><?php echo get_bloginfo( 'description'); ?></span>
            <?php  } ?>
        </div>
        <div class="main-content-navsidebar">
            <?php dynamic_sidebar('nav-sidebar'); ?>
        </div>        
    </div>
    <?php } ?>
    <div class="dosislite-menu-touch">
        <div class="logo-navbar">
            <?php get_template_part( 'template-parts/header','logo'); ?>
        </div>
        <div class="main-menu-vertical">
            <?php
                wp_nav_menu( array (
                    'container' => false,
                    'theme_location' => 'primary',
                    'menu_class' => 'dosislite-main-menu',
                    'depth' => 3,
                ) );
            ?>
        </div>
    </div>
    <div class="main-wrapper-boxed">
        <header id="dosislite-header" class="header">
            <div class="container">
                <div class="dosislite-logo">
                    <?php get_template_part( 'template-parts/header','logo'); ?>
                </div>
                <?php
                    $dosislite_menu_class = 'no_has_navbar';
                    if ( is_active_sidebar('nav-sidebar') ) {
                        $dosislite_menu_class = 'has_navbar';
                    }
                ?>
                <div class="header-content <?php echo esc_attr( $dosislite_menu_class ); ?>"> 
                    <div class="navbar-col navbar-left">                        
                        <a href="javascript:void(0)" class="navbar-touch">
                            <div class="navbar-toggle">
                                <span></span>
                            </div>
                        </a>
                    </div>
                    <div class="navbar-main">
                        <div id="nav-wrapper" class="nav-main main-menu-horizontal">
                            <?php
                                wp_nav_menu( array (
                                    'container' => false,
                                    'theme_location' => 'primary',
                                    'menu_class' => 'dosislite-main-menu',
                                    'depth' => 3,
                                ) );
                            ?>
                        </div>
                    </div>
                    <div class="navbar-col navbar-end">
                        <a href="javascript:void(0)" class="menu-touch nav-right d-lg-none">
                            <div class="navbar-toggle">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <a class="navbar-search" href="javascript:void(0)"><i class="fas fa-search"></i></a>
                        <div class="nav-search-hear">
                            <?php get_search_form() ?>
                            <a href="javascript:void(0)" class="close-search"><i class="fas fa-times"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div id="content" class="dosislite-primary">
    