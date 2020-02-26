<?php

if ( function_exists('dosislite_require_file') )
{
    # Load Classes
    dosislite_require_file( DOSISLITE_CORE_CLASSES . 'class-tgm-plugin-activation.php' );
    
    # Load Functions
    dosislite_require_file( DOSISLITE_CORE_FUNCTIONS . 'customizer/customizer.php' );
    dosislite_require_file( DOSISLITE_CORE_FUNCTIONS . 'customizer/dosislite_customizer_style.php' );
    dosislite_require_file( DOSISLITE_CORE_FUNCTIONS . 'dosislite_resize_image.php' );

    // Load Widgets
    dosislite_require_file( DOSISLITE_CORE_WIDGETS . 'latest_post.php' );
}
