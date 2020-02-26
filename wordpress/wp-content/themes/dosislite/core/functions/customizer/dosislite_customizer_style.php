<?php
function dosislite_custom_css()
{
    $custom_css = "";
    if ( get_theme_mod('dosislite_accent_color') )
    {
        $accent_color = esc_attr(get_theme_mod('dosislite_accent_color'));
        $custom_css .= "
            a, .sticky .post-title:before,.screen-reader-text:focus,
            .post-title a:hover, .wp-block-archives li a:hover, 
            .wp-block-categories li a:hover, .dosislite-main-menu li a:hover,
            .item-post-feature .post-cats a:hover,.widget ul li a:hover,
            .dosislite-social a:hover{
                color: {$accent_color};
            }

            .dosislite-button, button, .button, input[type='submit'],
            .chosen-container .chosen-results li.highlighted,
            .dosislite-pagination .nav-links .page-numbers:hover,
            .dosislite-pagination .nav-links .page-numbers.current,
            .tagcloud a:hover,
            .post-cats a{
                background-color: {$accent_color};
            }

            .blog-grid .date-post:after,
            .tagcloud a:hover{
                border-color: {$accent_color};
            }
        ";
    }

    if (get_theme_mod( 'dosislite_body_color' ) ) {
        $body_color = esc_attr(get_theme_mod('dosislite_body_color'));
        $custom_css .= "
            body{
                color:{$body_color};
            }
        ";
    }
    
    wp_add_inline_style( 'dosislite-style', $custom_css );
}

add_action( 'wp_enqueue_scripts', 'dosislite_custom_css', 15 );
