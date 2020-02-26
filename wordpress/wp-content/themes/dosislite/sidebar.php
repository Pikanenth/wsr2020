<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dosislite
 */

if ( ! is_active_sidebar( 'sidebar' ) ) {
	return;
}
?>

<aside id="sidebar" class="sidebar">
	<?php if ( is_active_sidebar('sidebar') ) { dynamic_sidebar('sidebar'); } ?>
</aside>
