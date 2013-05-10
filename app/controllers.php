<?php

$app->get('/api/search/{keyword}', function($keyword) use($app) {
    return json_encode(array('keyword: '=> $keyword, 'result' => 'blah'));
});

$app->get('/', function() {
    return 'home page';
});
