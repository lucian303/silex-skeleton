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
use App\User\UserProvider;

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

$app->register(new HttpCacheServiceProvider(), array(
	'http_cache.cache_dir' => __DIR__ . '/../cache/',
	'http_cache.esi'       => null,
));

$app->register(new ValidatorServiceProvider());

$app->register(new FormServiceProvider());
$app['form.secret'] = md5(time());

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
        'users' => $app->share(function () use ($app) {
	        return new UserProvider($app['db']); // use custom userprovider to auth with db
        }),
    ),
);


 $app['security.encoder.digest'] = $app->share(function () {
	 // sha512, base64encoded, 3 iteration hash
     return new MessageDigestPasswordEncoder('sha512', true, 3); // three hash passes should be plenty
 });

//echo $app['security.encoder.digest']->encodePassword('password', ''); die; // print a password hash

$app->register(new TranslationServiceProvider(array(
    'locale_fallback' => 'en',
)));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../log/app.log',
    'monolog.name'    => 'silex',
    'monolog.level'   => Logger::INFO,
));

$app->register(new DoctrineServiceProvider());

/** @return $app Silex\Application */
return $app;
