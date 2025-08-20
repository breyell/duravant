<?php

if (!function_exists('timber_vite_tiny_mce_before_init')) {
	function timber_vite_tiny_mce_before_init($mce_init)
	{
		$mce_init['formats'] = json_encode([
			'lead' => [
				'selector' => 'p',
				'block'    => 'p',
				'classes'  => 'lead',
			],
		], JSON_THROW_ON_ERROR);

		$block_formats = [
			'Paragraph=p',
			'Lead Paragraph=lead',
			'Heading 2=h2',
			'Heading 3=h3',
			'Heading 4=h4',
			'Heading 5=h5',
			'Heading 6=h6',
			'Preformatted=pre',
		];
		$mce_init['block_formats'] = implode(';', $block_formats);

		return $mce_init;
	}
}
add_filter('tiny_mce_before_init', 'timber_vite_tiny_mce_before_init');

function my_theme_add_editor_styles()
{
	add_editor_style('wp-editor-style.css');
}
add_action('init', 'my_theme_add_editor_styles');

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
