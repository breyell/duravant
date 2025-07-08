<?php

if (!function_exists('dd')) :
	/**
	 * Dump and die.
	 *
	 * @param mixed $value The value to dump and die.
	 * @return void
	 */
	function dd($value)
	{
		var_dump($value); // skipcq: PHP-A1012
		die();
	}
endif;

if (!function_exists('enqueue_chunk')) :
	/**
	 * Enqueues a chunk from the Vite manifest.
	 *
	 * Handles both CSS and JS chunks, including dependencies and CSS imports.
	 * In development, skips CSS imports since they're handled by Vite.
	 *
	 * @param string $name The name of the chunk to enqueue.
	 * @return void
	 */
	function enqueue_chunk($name)
	{
		$chunk = manifest($name);

		if (!$chunk) {
			return;
		}

		$prefix = prefix('module-');
		$handle = $prefix . $name;
		$src = get_assets_directory() . '/' . $chunk['file'];
		$deps = [];

		foreach ($chunk['imports'] ?? [] as $import) {
			enqueue_chunk($import);

			$deps[] = $prefix . $import;
		}

		if (str_ends_with($src, '.css')) {
			wp_enqueue_style($handle, $src, [], null);
		} else {
			wp_enqueue_script($handle, $src, $deps, null);
			// wp_enqueue_script($handle, $src, $deps, null, ['strategy'  => 'defer']);
		}

		if (environment('development')) {
			return;
		}

		foreach ($chunk['css'] ?? [] as $css) {
			wp_enqueue_style($handle, get_assets_directory() . '/' . $css, [], null);
		}
	}
endif;

if (!function_exists('environment')) :
	/**
	 * Get the current environment or check if we're in specific environment(s).
	 *
	 * Returns the current environment ('development' or 'production') if no arguments
	 * are provided. If environment(s) are provided, checks if current matches any.
	 *
	 * @param string|array|null $environments Environment(s) to check against.
	 * @return string|bool Environment string if no args, boolean if checking environments.
	 */
	function environment($environments = null)
	{
		$environment = file_exists(get_theme_file_path('hot')) ? 'development' : 'production';

		if (!$environments) {
			return $environment;
		}

		if (!is_array($environments)) {
			$environments = [$environments];
		}

		return in_array($environment, $environments);
	}
endif;

if (!function_exists('get_assets_directory')) :
	/**
	 * Get the assets directory for the theme.
	 *
	 * In development, returns the Vite dev server URL from the 'hot' file.
	 * In production, returns the theme's dist directory.
	 *
	 * @return string The assets directory URL.
	 */
	function get_assets_directory()
	{
		if (environment('development')) {
			return str_replace(
				site_url(),
				rtrim(file_get_contents(get_theme_file_path('hot')), '/'),
				get_stylesheet_directory_uri()
			);
		}

		return get_stylesheet_directory_uri() . '/dist';
	}
endif;

if (!function_exists('manifest')) :
	/**
	 * Get the manifest file for the theme.
	 *
	 * @param string $name The name of the manifest file.
	 * @return array|null The manifest file or null if not found.
	 */
	function manifest($name)
	{
		if (environment('development')) {
			return $name ? ['file' => $name] : [];
		}

		static $manifest;

		if (!$manifest) {
			$manifest = json_decode(
				file_get_contents(get_assets_directory() . '/manifest.json'),
				true
			);
		}

		return $name ? $manifest[$name] ?? null : $manifest;
	}
endif;

if (!function_exists('prefix')) :
	/**
	 * Prefix a value with 'fb-' if it's not empty.
	 *
	 * @param string|null $value The value to prefix.
	 * @return string The prefixed value.
	 */
	function prefix($value = null)
	{
		return 'fb-' . ($value ?: '');
	}
endif;
