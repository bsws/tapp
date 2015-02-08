<?php
// configure your app for the production environment
$app['debug']  = true;

$app['twig.path'] = array(__DIR__.'/../src/templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

$app['christiantour.json_offers_file'] = __DIR__.'/../api_response.txt';
