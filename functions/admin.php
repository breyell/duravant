<?php

if (!function_exists('timber_vite_admin_menu')) :
	/**
	 * Remove unnecessary admin menu pages.
	 */
	function timber_vite_admin_menu()
	{
		remove_menu_page('edit-comments.php');

		if (environment('production')) {
			remove_menu_page('edit.php?post_type=acf-field-group');
		}
	}
endif;
add_action('admin_menu', 'timber_vite_admin_menu');

// Disable Gutenberg on the back end.
add_filter('use_block_editor_for_post', '__return_false');

// Disable Gutenberg for widgets.
add_filter('use_widgets_block_editor', '__return_false');

add_action('wp_enqueue_scripts', function () {
	// Remove CSS on the front end.
	wp_dequeue_style('wp-block-library');

	// Remove Gutenberg theme.
	wp_dequeue_style('wp-block-library-theme');

	// Remove inline global CSS on the front end.
	wp_dequeue_style('global-styles');

	// Remove classic-themes CSS for backwards compatibility for button blocks.
	wp_dequeue_style('classic-theme-styles');
}, 20);

// Hide Posts from the sidebar
add_action('admin_menu', 'remove_default_post_type');

function remove_default_post_type()
{
	remove_menu_page('edit.php');
}

// Hide Posts from admin bar
add_action('admin_bar_menu', 'remove_default_post_type_menu_bar', 999);

function remove_default_post_type_menu_bar($wp_admin_bar)
{
	$wp_admin_bar->remove_node('new-post');
}

function remove_add_new_post_href_in_admin_bar()
{
?>
	<script type="text/javascript">
		function remove_add_new_post_href_in_admin_bar() {
			var add_new = document.getElementById('wp-admin-bar-new-content');
			if (!add_new) return;
			var add_new_a = add_new.getElementsByTagName('a')[0];
			if (add_new_a) add_new_a.setAttribute('href', '#!');
		}
		remove_add_new_post_href_in_admin_bar();
	</script>
<?php
}
add_action('admin_footer', 'remove_add_new_post_href_in_admin_bar');

function remove_frontend_post_href()
{
	if (is_user_logged_in()) {
		add_action('wp_footer', 'remove_add_new_post_href_in_admin_bar');
	}
}
add_action('init', 'remove_frontend_post_href');

// Remove Posts from Quick Draft Dashboard Widget
add_action('wp_dashboard_setup', 'remove_draft_widget', 999);

function remove_draft_widget()
{
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
}
