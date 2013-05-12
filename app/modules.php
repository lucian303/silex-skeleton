<?php

use Silex\Provider\TwigServiceProvider;

// Iterate through all modules and all all routes from any php files inside the controller directories
// Each controller file must return an instance of $app
$path = __DIR__ . '/modules/';
$dirIterator = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
$fileIterator = new IteratorIterator($dirIterator);
$viewDirs = [];

foreach ($fileIterator as $modulePath => $module) {
	foreach(new RecursiveDirectoryIterator($module, FilesystemIterator::SKIP_DOTS) as $dirName => $directory) {
		$dirNameSplit = explode(DIRECTORY_SEPARATOR, $dirName);

		// Require controller files (must return $app back)
		if ('controllers' == $dirNameSplit[count($dirNameSplit) - 1]) {
			foreach(new RecursiveDirectoryIterator($dirName, FilesystemIterator::SKIP_DOTS) as $controllerName => $controller) {
				$app = require_once($controllerName);
			}
		}
		else if ('views' == $dirNameSplit[count($dirNameSplit) - 1]) {
			// Get a list of views directories to pass to twig or hopfeully Zend_View soon
			$viewDirs[] = $dirName;
		}
	}
}

$app->register(new TwigServiceProvider(), array(
    'twig.options'        => array(
        'cache'            => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
        'strict_variables' => true
    ),
    'twig.form.templates' => array('form_div_layout.html.twig', 'templates/form_div_layout.html.twig'),
    'twig.path'           => $viewDirs,
));

/** @vreturn $app Silex\Application */
return $app;
