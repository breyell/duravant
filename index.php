<?php

use Timber\Timber;

$context = Timber::context();

// if (is_404()) {
//     Timber::render('templates/404.twig', Timber::context());
// }

return Timber::render('templates/page-builder.twig', $context);
