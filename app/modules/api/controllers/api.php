<?php

/** @var $app App\Application */
$api = $app['controllers_factory'];

// Add controllers for API here
$api->get('/search/{name}', function($name) use($app) {
	return $app->json(array($name));
});

$app->mount('/api', $api);
return $app;