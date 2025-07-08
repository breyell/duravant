<?php

require __DIR__ . '/vendor/autoload.php';

$directory = get_stylesheet_directory() . '/functions';
$iterator = new RecursiveDirectoryIterator($directory);
$path_names = new RecursiveRegexIterator($iterator, '/\.php$/');

foreach ($path_names as $path_name) {
	require_once $path_name;
}
