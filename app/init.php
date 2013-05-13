<?php

use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Monolog\Logger;

$app = new Silex\Application();

// Load environment specific config
$environmentConfigFileName = getenv('SILEX_ENV') . '.php';
require_once __DIR__ . "/config/" . $environmentConfigFileName;

// Register Service Providers
$app->register(new SessionServiceProvider());
$app['session.storage.options'] = array(
	'name' => 'silex',
	'id' => 'silex',
	'cookie_lifetime' => 0, // browser session
	'cookie_path' => '/',
	'cookie_secure' => false, // TODO: change for prod
	'cookie_httponly' => true,
);

$app->register(new HttpCacheServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new UrlGeneratorServiceProvider());

$app->register(new SecurityServiceProvider());
$app['security.firewalls'] = array(
    'login' => array(
        'pattern' => '^/login$',
    ),
    'secured' => array(
        'pattern' => '^/.*$',
        'form' => array('login_path' => '/login', 'check_path' => '/login_check'),
        'logout' => array('logout_path' => '/logout'),
//        'users' => $app->share(function () use ($app) {
//            return new UserProvider($app['db']);
//        }),
        'users' => array(
            'admin' => array('ROLE_ADMIN', '5f4dcc3b5aa765d61d8327deb882cf99'),
        ),
    ),
);


 $app['security.encoder.digest'] = $app->share(function () {
	 // md5 for testing
     return new MessageDigestPasswordEncoder('md5', false, 0);
 });

$app->register(new TranslationServiceProvider(array(
    'locale_fallback' => 'en',
)));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../log/app.log',
    'monolog.name'    => 'app',
    'monolog.level'   => Logger::INFO,
));

$app->register(new DoctrineServiceProvider());

/** @return $app Silex\Application */
return $app;
