<?php
/**
 * Created by PhpStorm.
 * User: yupal
 * Date: 2/11/2019
 * Time: 7:00 PM
 */

namespace ColibriWP\Theme\BuilderComponents;


use ColibriWP\Theme\View;
use function function_exists;

class WooContent extends BuilderComponentBase {

	/**
	 * @return string
	 */
	protected function getName() {
		return 'main';
	}

	public function render($parameters = array()) {

		$self = $this;
		View::printIn( View::CONTENT_ELEMENT, function () use ( $self ) {
			/** SECTION START */
			View::printIn( View::SECTION_ELEMENT, function () use ( $self ) {
				/** ROW START */
				View::printIn( View::ROW_ELEMENT, function () use ( $self ) {

					/** COLUMN START */
					View::printIn( View::COLUMN_ELEMENT, function () use ( $self ) {
					    if (function_exists('woocommerce_content')) {
		                            woocommerce_content();
		                        }
					} );

					/** COLUMN END */

		                    View::partial( 'sidebar', 'product', array(
		                        "component" => $this,
		                    ) );

				}, array(
					'outer_class' => array(),
					'inner_class' => array()
				) );
				/** ROW END */
			} );
			/** SECTION END */
		}, array( array( 'blog-page' ) ) );
	}

	public function parentRender() {
		parent::render();
	}


}
