<?php
// configure your app for the production environment
$app['debug']  = true;

$app['twig.path'] = array(__DIR__.'/../src/templates');
$app['twig.options'] = array('cache' => __DIR__.'/../var/cache/twig');

$app['christiantour.api_user']      = 'travelexpert';
$app['christiantour.api_password']  = '8Zmq9wnF';
$app['christiantour.api_url']       = 'http://www.christiantour.ro/reseller_api/index';
$app['christiantour.json_offers_file']      = __DIR__.'/../api_response.txt';
$app['christiantour.api_request_method']    = 'POST';
