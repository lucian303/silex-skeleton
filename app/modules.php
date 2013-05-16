<?php

use Silex\Provider\TwigServiceProvider;

/**
 * Iterate through all modules and all all routes from any php files inside the controller
 * and view directories
 * Each controller file must return an instance of $app
 */
$viewDirs = array(__DIR__ . '/layouts');
$path = __DIR__ . '/modules';
$dirIterator = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
$fileIterator = new IteratorIterator($dirIterator);

foreach ($fileIterator as $modulePath => $module) {
	foreach(new RecursiveDirectoryIterator($module, FilesystemIterator::SKIP_DOTS) as $dirName => $directory) {
		$dirNameSplit = explode(DIRECTORY_SEPARATOR, $dirName);
		$lastDir = $dirNameSplit[count($dirNameSplit) - 1];

		if ('controllers' == $lastDir) {
			foreach(new RecursiveDirectoryIterator($dirName, FilesystemIterator::SKIP_DOTS) as $controllerName => $controller) {
				// Required controller files (must return $app back)
				$app = require_once($controllerName);
			}
		}
		else if ('views' == $lastDir) {
			// Get a list of views directories to pass to twig
			$viewDirs[] = $dirName;
		}
	}
}

/** @var $app App\Application */
$app->register(new TwigServiceProvider(), array(
    'twig.options'        => array(
        'cache'            => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
        'strict_variables' => true
    ),
//    'twig.form.templates' => array('form_div_layout.html.twig', 'templates/form_div_layout.html.twig'),
    'twig.path'           => $viewDirs,
));

/** @return $app Silex\Application */
return $app;
