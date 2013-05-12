<?php

$app->get('/', function() use ($app) {
    return $app['twig']->render('main.html.twig', array());
});

$app->get('/write', function() use ($app) {
    return $app['twig']->render('main.htmltwig', array());
});

/** @return $app Silex\Application */
return $app;
