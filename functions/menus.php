<?php

if (!function_exists('timber_vite_menus')) :
	function timber_vite_menus()
	{
		register_nav_menus([
			'primary' => 'Primary',
			'footer' => 'Footer',
		]);
	}
endif;

add_action('init', 'timber_vite_menus');
