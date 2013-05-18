<?php

use Symfony\Component\HttpFoundation\Request;

/** @var $app App\Application */
/** @var $api Silex\ControllerCollection */
$api = $app['controllers_factory'];

// Add controllers for API here
$api->get('/search/{name}', function($name) use($app) {
	return $app->json(array("hello $name"));
});

$app->mount('/api', $api);

/** @return $app Silex\Application */
return $app;
