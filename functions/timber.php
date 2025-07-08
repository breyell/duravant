<?php

// require_once __DIR__ . '/../src/ExampleCustomPostType.php';

use Timber\Timber;
use Twig\TwigFunction;

Timber::init();

add_filter('timber/context', function ($context) {
	$context['global'] = get_fields('option');
	$context['primary_menu'] = Timber::get_menu('primary');
	$context['footer_menu'] = Timber::get_menu('footer');
	return $context;
});

function add_image_sizes()
{
	add_image_size('small_custom', 512, 512);
	add_image_size('medium_custom', 768, 768);
	add_image_size('large_custom', 1024, 1024);
	add_image_size('xlarge_custom', 1280, 1280);
	add_image_size('xxlarge_custom', 1920, 1920);
	add_image_size('xxxlarge_custom', 3000, 3000);
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
