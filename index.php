<?php

use Timber\Timber;

$context = Timber::context();

if (is_404()) {
	return Timber::render('templates/404.twig', Timber::context());
}

$context['breadcrumbs'] = [$context['post']];

if ($context['post']->post_parent !== 0) {
	do {
		$parent_page_id = $context['post']->post_parent;
		$parent_page = Timber::get_post($parent_page_id);
		array_unshift($context['breadcrumbs'], $parent_page);
		$parent_page_id = $parent_page->post_parent;
	} while ($parent_page_id !== 0);
}
array_unshift($context['breadcrumbs'], Timber::get_post(get_option('page_on_front')));

$post_type = get_post_type();

if ($post_type === 'solution') {
	array_splice($context['breadcrumbs'], 1, 0, [Timber::get_post($context['global']['solutions_page'])]);
	if (wp_get_post_parent_id() === 0) {
		return Timber::render('templates/solution.twig', $context);
	}
} elseif ($post_type === 'market') {
	array_splice($context['breadcrumbs'], 1, 0, [Timber::get_post($context['global']['markets_page'])]);

	return Timber::render('templates/market.twig', $context);
} elseif ($post_type === 'blog') {
	array_splice($context['breadcrumbs'], 1, 0, [Timber::get_post($context['global']['blog_page'])]);

	return Timber::render('templates/blog-post.twig', $context);
}

return Timber::render('templates/page-builder.twig', $context);
