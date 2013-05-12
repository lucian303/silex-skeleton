<?php

use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Monolog\Logger;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

$app = new Silex\Application();

// TODO: Set this based on config
$app['debug'] = true;

//$app->register(new HttpCacheServiceProvider());
//$app->register(new SessionServiceProvider());
//$app->register(new ValidatorServiceProvider());
//$app->register(new FormServiceProvider());
//$app->register(new UrlGeneratorServiceProvider());
//
// $app->register(new SecurityServiceProvider(), array(
//     'security.firewalls' => array(
//         'admin' => array(
//             'pattern' => '^/',
//             'form'    => array(
//                 'login_path'         => '/login',
//                 'username_parameter' => 'form[username]',
//                 'password_parameter' => 'form[password]',
//             ),
//             'logout'    => true,
//             'anonymous' => true,
//             'users'     => $app['security.users'],
//         ),
//     ),
// ));

 $app['security.encoder.digest'] = $app->share(function () {
     return new MessageDigestPasswordEncoder();
 });

$app->register(new TranslationServiceProvider(array(
    'locale_fallback' => 'en',
)));

$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../log/app.log',
    'monolog.name'    => 'app',
    'monolog.level'   => Logger::INFO,
));

$app->register(new TwigServiceProvider(), array(
    'twig.options'        => array(
        'cache'            => isset($app['twig.options.cache']) ? $app['twig.options.cache'] : false,
        'strict_variables' => true
    ),
    'twig.form.templates' => array('form_div_layout.html.twig', 'templates/form_div_layout.html.twig'),
    'twig.path'           => array(__DIR__ . '/views'),
));

$app->register(new Silex\Provider\DoctrineServiceProvider());

/** @vreturn $app Silex\Application */
return $app;