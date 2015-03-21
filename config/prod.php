<?php
// configure your app for the production environment
ini_set('display_errors', 'On');
error_reporting(E_ALL);

$app['debug']  = true;

$app['twig.path'] = array(__DIR__.'/../src/templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

$app['christiantour.api_user']      = getenv('CHRISTIANTOUR_API_USER');
$app['christiantour.api_password']  = getenv('CHRISTIANTOUR_API_PASS');
$app['christiantour.api_url']       = 'http://www.christiantour.ro/reseller_api/index';
$app['christiantour.json_offers_file']          = __DIR__.'/../api_response.txt';
$app['christiantour.json_destinations_file']    = __DIR__.'/../api_response_destinations.txt';
$app['christiantour.api_request_method']    = 'POST';
