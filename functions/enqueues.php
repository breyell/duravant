<?php

if (! function_exists('timber_vite_wp_enqueue_scripts')) :
	/**
	 * Enqueue scripts for the theme.
	 */
	function timber_vite_wp_enqueue_scripts()
	{
		// Enqueue the Vite client if we're in dev
		if (environment('development')) {
			enqueue_chunk('@vite/client');
		}

		// Enqueue the main entry points
		enqueue_chunk('style.css');
		enqueue_chunk('main.js');

		// Enqueue external styles and scripts
		// wp_enqueue_style(
		//     prefix( 'google-fonts' ),
		//     'https://fonts.googleapis.com/css2?family=Open+Sans&display=swap',
		//     [],
		//     null
		// );
	}
endif;
add_action('wp_enqueue_scripts', 'timber_vite_wp_enqueue_scripts');

if (! function_exists('timber_vite_wp_enqueue_block_editor_assets')) :
	/**
	 * Enqueue scripts for the block editor.
	 */
	function timber_vite_wp_enqueue_block_editor_assets()
	{
		timber_vite_wp_enqueue_scripts();
	}
endif;
add_action('enqueue_block_editor_assets', 'timber_vite_wp_enqueue_block_editor_assets');

if (! function_exists('timber_vite_wp_script_attributes')) :
	/**
	 * Filter to add module attributes to script tags.
	 *
	 * @param array $attributes The attributes of the script tag.
	 * @return array The modified attributes.
	 */
	function timber_vite_wp_script_attributes($attributes)
	{
		if (str_starts_with($attributes['id'] ?? '', prefix('module-'))) {
			$attributes['type'] = 'module';
		}

		return $attributes;
	}
endif;
add_filter('wp_script_attributes', 'timber_vite_wp_script_attributes');
add_filter('wp_inline_script_attributes', 'timber_vite_wp_script_attributes');

if (! function_exists('timber_vite_wp_preload_resources')) :
	/**
	 * Preload resources for the theme.
	 *
	 * During development, preloading is disabled to prevent HMR issues.
	 * In production, preloads theme styles and scripts for performance.
	 *
	 * @param array $preload_resources Array of resources to preload.
	 * @return array Modified array of resources to preload.
	 */
	function timber_vite_wp_preload_resources($preload_resources)
	{
		// Don't preload resources during dev because it causes HMR to break
		if (! environment('development')) {
			global $wp_styles;

			// Preload enqueued theme styles
			foreach ($wp_styles->queue as $handle) {
				if (str_starts_with($handle, prefix())) {
					$preload_resources[] = [
						'href' => $wp_styles->registered[$handle]->src,
						'as'   => 'style',
					];
				}
			}
		}

		// Preload enqueued theme scripts
		global $wp_scripts;

		foreach ($wp_scripts->queue as $handle) {
			if (
				str_starts_with($handle, prefix())
				&& ! str_starts_with($handle, prefix('module-'))
			) {
				$preload_resources[] = [
					'href' => $wp_scripts->registered[$handle]->src,
					'as'   => 'script',
				];
			}
		}

		return $preload_resources;
	}
endif;
add_filter('wp_preload_resources', 'timber_vite_wp_preload_resources');

if (! function_exists('timber_vite_modulepreload_resources')) :
	/**
	 * Preload module scripts for the theme.
	 *
	 * @return void
	 */
	function timber_vite_modulepreload_resources()
	{
		global $wp_scripts;

		// Preload modules
		foreach ($wp_scripts->queue as $handle) {
			if (str_starts_with($handle, prefix('module-'))) {
				$href = $wp_scripts->registered[$handle]->src;

				echo "<link rel='modulepreload' href='$href' />\n";
			}
		}
	}
endif;
add_action('wp_head', 'timber_vite_modulepreload_resources', 1);
