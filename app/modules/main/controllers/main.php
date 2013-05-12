<?php

$app->get('/', function() use ($app) {
    return $app['twig']->render('main.twig', array());
});

$app->get('/write', function() use ($app) {
    return $app['twig']->render('main.twig', array());
});

/** @return $app Silex\Application */
return $app;
