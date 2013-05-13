<?php

use Symfony\Component\HttpFoundation\Request;

/** @var $app App\Application */
$app->get('/', function() use ($app) {
    return $app['twig']->render('main.html.twig', array());
});

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
});

/** @return $app App\Application */
return $app;
