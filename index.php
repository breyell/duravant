<?php

use Timber\Timber;

$context = Timber::context();
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

// if (is_404()) {
//     Timber::render('templates/404.twig', Timber::context());
// }

return Timber::render('templates/page-builder.twig', $context);
