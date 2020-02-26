<?php
/**
 * Created by PhpStorm.
 * User: yupal
 * Date: 2/11/2019
 * Time: 7:02 PM
 */

namespace ColibriWP\Theme\Components;


use ColibriWP\Theme\Theme;
use ColibriWP\Theme\View;

class WooContent extends MainContent {

    public function renderContent() {

        View::printIn( View::CONTENT_ELEMENT, function () {
            View::printIn( View::SECTION_ELEMENT, function () {
                View::printIn( View::ROW_ELEMENT, function () {
                    View::printIn( View::COLUMN_ELEMENT, function () {
                        if (function_exists('woocommerce_content')) {
                            woocommerce_content();
                        }
                    } );
                    View::partial( 'sidebar', 'product', array(
                        "component" => $this,
                    ) );
                } );
            } );
        }, array( array( 'woo-page', 'colibri-woo-main-content-area' ) ) );
    }
}
