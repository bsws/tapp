<?php

use Herrera\Pdo\PdoServiceProvider;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

use Travel\Handlers\OffersHandler;
use Travel\Handlers\OffersDbHandler;
use Travel\Handlers\ProviderDbHandler;
use Travel\Handlers\ProviderHandler;
use Travel\Handlers\CountriesDbHandler;
use Travel\Handlers\CountriesHandler;
use Travel\Handlers\DestinationsDbHandler;
use Travel\Handlers\DestinationsHandler;
use Travel\Handlers\HotelsDbHandler;
use Travel\Handlers\HotelsHandler;
use Travel\Handlers\TransportDbHandler;
use Travel\Handlers\TransportHandler;
use Travel\Handlers\ImagesDbHandler;
use Travel\Handlers\ImagesHandler;

use Travel\Util\Utils;

use Travel\Controller\ImportController;
use Travel\Controller\AdminController;
use Travel\Controller\OffersController;

$app = new Application();

$app->register(new ServiceControllerServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'travel',
        'user'      => 'root',
        'password'  => ',marius++',
        'charset'   => 'utf8',
    ),
));

//$app['security.firewalls'] = array(
//    'admin' => array(
//        'pattern' => '^/admin/',
//        'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
//        'users' => array(
//            // raw password is foo
//            'admin' => array('ROLE_ADMIN', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='),
//        ),
//    ),
//); 
//
//
//$app->register(new Silex\Provider\SecurityServiceProvider());

$app->register(new TwigServiceProvider());

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return $app['request_stack']->getMasterRequest()->getBasepath().'/'.$asset;
    }));
    return $twig;
});

//$app->before(function () use ($app) {
//    $app['twig']->addGlobal('layout.html', $app['twig']->loadTemplate('layout.html'));
//}); 

$app['offersDbHandler'] = $app->share(function() use($app) {
    return new OffersDbHandler($app['db']);
});

//offers handler service
$app['offersHandler'] = $app->share(function() use($app){
    return new OffersHandler($app['offersDbHandler']);
});

$app['providerDbHandler'] = $app->share(function() use($app) {
    return new ProviderDbHandler($app['db']);
});

$app['providerHandler'] = $app->share(function() use($app){
    return new ProviderHandler($app['providerDbHandler']);
});

$app['countriesDbHandler'] = $app->share(function() use($app) {
    return new CountriesDbHandler($app['db']);
});

$app['countriesHandler'] = $app->share(function() use($app){
    return new CountriesHandler($app['countriesDbHandler']);
});

$app['destinationsDbHandler'] = $app->share(function() use($app) {
    return new DestinationsDbHandler($app['db']);
});

$app['destinationsHandler'] = $app->share(function() use($app){
    return new DestinationsHandler($app['destinationsDbHandler']);
});

$app['hotelsDbHandler'] = $app->share(function() use($app) {
    return new HotelsDbHandler($app['db']);
});

$app['hotelsHandler'] = $app->share(function() use($app){
    return new HotelsHandler($app['hotelsDbHandler']);
});

$app['transportDbHandler'] = $app->share(function() use($app) {
    return new TransportDbHandler($app['db']);
});

$app['transportHandler'] = $app->share(function() use($app){
    return new TransportHandler($app['transportDbHandler']);
});

$app['imagesDbHandler'] = $app->share(function() use($app) {
    return new ImagesDbHandler($app['db']);
});

$app['imagesHandler'] = $app->share(function() use($app){
    return new ImagesHandler($app['imagesDbHandler']);
});

$app['utils'] = $app->share(function() use($app){
    return new Utils();
});

//controllers
$app['controller.import'] = $app->share(function() use ($app) {
    return new ImportController();
});
$app['controller.admin'] = $app->share(function() use ($app) {
    return new AdminController();
});
$app['controller.offers'] = $app->share(function() use ($app) {
    return new OffersController();
});


function pr($param) {
    echo '<pre>';
    print_r($param);
    echo '</pre>';
}

function prd($param) {
    echo '<pre>';
    print_r($param);
    die;
}

function prdu($param) {
    echo '<pre>';
    var_dump($param);
}

function prdud($param) {
    echo '<pre>';
    var_dump($param);
    die;
}

return $app;
