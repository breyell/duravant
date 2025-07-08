<?php

add_action('init', function () {
	global $wp_taxonomies;
	unregister_taxonomy_for_object_type('category', 'post');
	unregister_taxonomy_for_object_type('post_tag', 'post');
	unregister_taxonomy('category');
	unregister_taxonomy('post_tag');
});
