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
use SilexAssetic\AsseticServiceProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Monolog\Logger;
use App\User\UserProvider;

// Register global error handler to turn errors into exceptions
ErrorHandler::register();

$app = new App\Application();

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../log/app.log',
    'monolog.name'    => 'silex',
    'monolog.level'   => Logger::INFO,
));

$app->error(function (\Exception $e, $code) use ($app) {
	$app['monolog']->err(var_export($e, true));

	if (!$app['debug']) {
	    switch ($code) {
	        case 404:
	            $message = 'The requested page could not be found.';
	            break;
	        default:
	            $message = 'We are sorry, but something went terribly wrong.';
	    }

		return new Response($message, $code);
	}

	throw $e;
});


// Load environment specific config
$environmentConfig = trim(getenv('SILEX_ENV'));
$envs = array('dev', 'test', 'stage', 'prod');
if (in_array($environmentConfig, $envs)) {
	$fileName = __DIR__ . "/config/" . $environmentConfig . '.php';
	require_once $fileName;
}
else {
	$app['monolog']->err('Environment config not loaded. Environment not in array.');
}

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
	'locale' => 'en',
    'locale_fallback' => 'en',
)));

$app->register(new DoctrineServiceProvider());

if (isset($app['assetic.enabled']) && $app['assetic.enabled']) {
    $app->register(new AsseticServiceProvider(), array(
        'assetic.options' => array(
            'debug'            => $app['debug'],
            'auto_dump_assets' => $app['debug'],
        ),
        'assetic.filters' => $app->protect(function($fm) use ($app) {
            $fm->set('lessphp', new Assetic\Filter\LessphpFilter());
        }),
        'assetic.assets' => $app->protect(function($am, $fm) use ($app) {
            $am->set('styles', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_css'],
                    array($fm->get('lessphp'))
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('styles')->setTargetPath($app['assetic.output.path_to_css']);

            $am->set('scripts', new Assetic\Asset\AssetCache(
                new Assetic\Asset\GlobAsset(
                    $app['assetic.input.path_to_js']
                ),
                new Assetic\Cache\FilesystemCache($app['assetic.path_to_cache'])
            ));
            $am->get('scripts')->setTargetPath($app['assetic.output.path_to_js']);
        })
    ));
}

/** @return $app Silex\Application */
return $app;
