<?php

if (!function_exists('timber_vite_tiny_mce_before_init')) {
	function timber_vite_tiny_mce_before_init($mce_init)
	{
		error_log('TinyMCE Plugins: ' . print_r($mce_init, true));
		$mce_init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;';

		return $mce_init;
	}
}
add_filter('tiny_mce_before_init', 'timber_vite_tiny_mce_before_init');

// Add table controls to the TinyMCE toolbar
function add_tinymce_table_button($buttons)
{
	// Add table button if it's not already present

	array_push($buttons, 'table');

	return $buttons;
}
add_filter('mce_buttons', 'add_tinymce_table_button');

function enable_tinymce_table_plugin($plugins)
{
	// Add the table plugin to the array of TinyMCE plugins
	$plugins['table'] = get_template_directory_uri() . '/resources/php/tinymce-plugins/table/plugin.min.js';

	return $plugins;
}
add_filter('mce_external_plugins', 'enable_tinymce_table_plugin');
