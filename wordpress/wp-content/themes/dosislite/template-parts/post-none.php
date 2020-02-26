<header class="page-header">
	<h1 class="page-title"><?php esc_html_e('Nothing Found', 'dosislite'); ?></h1>
</header>
<div class="page-content error-page">
	<?php if (is_home() && current_user_can('publish_posts')) : ?>
	<p><?php printf(esc_html__('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'dosislite'), admin_url('post-new.php')); ?></p>
	<?php elseif (is_search()) : ?>
	<p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'dosislite'); ?></p>
	<?php get_search_form(); ?>
	<?php else : ?>
	<p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'dosislite'); ?></p>
	<?php get_search_form(); ?>
	<?php endif; ?>
</div>