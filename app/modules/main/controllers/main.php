<?php

use Symfony\Component\HttpFoundation\Request;

/** @var $app App\Application */
$app->get('/', function() use ($app) {
	/** @var $twig Twig_Environment*/
	$twig = $app['twig'];

    return $twig->render('main.html.twig', array());
});

$app->get('/login', function(Request $request) use ($app) {
	/** @var $session Symfony\Component\HttpFoundation\Session\Session */
	$session = $app['session'];

	/** @var $twig Twig_Environment*/
	$twig = $app['twig'];

    return $twig->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $session->get('_security.last_username'),
    ));
});

/** @return $app Silex\Application */
return $app;
