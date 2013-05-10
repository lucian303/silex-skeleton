<?php

$app->get('/api/search/{keyword}', function($keyword) use($app) {
    return json_encode(array('keyword: '=> $keyword, 'result' => 'blah', 'subobject' => array('subkey' => 'subvalue')));
});

$app->post('/api/add/{keyword}', function() use ($app) {
	return json_encode(array('result' => 'Item posted'));
});

$app->get('/', function() use ($app) {
    return $app['twig']->render('index.twig', array());
});

return $app;
