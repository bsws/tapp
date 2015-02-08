<?php

use Herrera\Pdo\PdoServiceProvider;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Travel\Util\OffersHandler;
use Travel\Util\OffersDbHandler;
use Travel\Controller\ImportController;

$app = new Application();

$app->register(new ServiceControllerServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new PdoServiceProvider()
    ,array(
        'pdo.dsn' => 'mysql:dbname=buzz;host=localhost',
        'pdo.username' => 'root',
        'pdo.password' => ',marius++',
        'pdo.options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
        )
    )
);

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    // add custom globals, filters, tags, ...
    $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return $app['request_stack']->getMasterRequest()->getBasepath().'/'.$asset;
    }));
    return $twig;
});

$app['offersDbHandler'] = $app->share(function() use($app) {
    return new OffersDbHandler($app['pdo']);
});

//offers handler service
$app['offersHandler'] = $app->share(function() use($app){
    return new OffersHandler($app['offersDbHandler']);
});

//controllers
$app['controller.import'] = $app->share(function() use ($app) {
    return new ImportController();
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

//routes

return $app;
