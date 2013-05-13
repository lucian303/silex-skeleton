<?php

use Symfony\Component\HttpKernel\Debug\ErrorHandler;

// Register global error handler to turn errors into exceptions
ErrorHandler::register();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/app.php';
