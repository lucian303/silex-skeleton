<?php

/** @var $app App\Application */
$app = require_once 'init.php';
$app = require_once 'modules.php';

$app['http_cache']->run();
