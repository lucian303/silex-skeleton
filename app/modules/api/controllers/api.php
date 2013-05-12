<?php

$app->get('/api/search/{keyword}', function($keyword) use($app) {
    return $app->json(array('keyword: '=> $keyword, 'result' => 'blah', 'subobject' => array('subkey' => 'subvalue')));
});

$app->post('/api/add/{keyword}', function() use ($app) {
	return $app->json(array('result' => 'Item posted'));
});

/** @return $app Silex\Application */
return $app;