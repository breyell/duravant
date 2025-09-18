<?php

require_once __DIR__ . '/../src/Brand.php';

use Timber\Timber;
use Twig\TwigFunction;

Timber::init();

add_filter('timber/context', function ($context) {
	$context['global'] = get_fields('option');
	$context['primary_menu'] = Timber::get_menu('primary');
	$context['utility_menu'] = Timber::get_menu('utility');
	$context['footer_menu'] = Timber::get_menu('footer');
	return $context;
});

add_filter('timber/post/classmap', function ($classmap) {
	$custom_classmap = [
		'brand' => Brand::class,
	];

	return array_merge($classmap, $custom_classmap);
});

function br_big_image_size_threshold($threshold)
{
	return 4000;
}
add_filter('big_image_size_threshold', 'br_big_image_size_threshold', 999, 1);

function add_image_sizes()
{
	add_image_size('512x512', 512, 512);
	add_image_size('768x768', 768, 768);
	add_image_size('1024x1024', 1024, 1024);
	add_image_size('1280x1280', 1280, 1280);
	add_image_size('1920x1920', 1920, 1920);
	add_image_size('2560x2560', 2560, 2560);
	add_image_size('3200x3200', 3200, 3200);
}
add_action('after_setup_theme', 'add_image_sizes');

add_filter('timber/twig', function (\Twig\Environment $twig) {
	$twig->addFunction(new TwigFunction('ResponsiveImage', function ($image, $attributes) {
		if (!(is_array($attributes) || is_string($attributes))) {
			throw new TypeError;
		}

		$image = Timber::get_post($image);

		if (is_string($attributes)) {
			$attributes = ['class' => $attributes];
		}

		$attributes = array_merge($attributes, [
			'src' => $image->src,
			'alt' => $image->alt,
			'width' => $image->width,
			'height' => $image->height,
		]);

		if (!str_ends_with($image->src, '.svg')) {
			$attributes = array_merge($attributes, [
				'srcset' => $image->srcset,
			]);
		}

		$html = '<img';

		foreach ($attributes as $name => $value) {
			$html .= $value === null
				? ' ' . $name
				: ' ' . $name . '="' . $value . '"';
		}

		$html .= '>';

		return $html;
	}));

	$twig->addFunction(new TwigFunction('ExtractOembedUrl', function ($string) {
		preg_match('/src="([^"]+)"/', $string, $match);
		$res = null;
		if (isset($match[1])) {
			$res = $match[1];
		}
		return $res;
	}));

	return $twig;
});
