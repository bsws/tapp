<?php

ini_set('display_errors', 'On');

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/app.php';

require __DIR__.'/../config/prod.php';
require __DIR__.'/../src/controllers.php';

$app->run();

/*
die;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->get('/ws', function() use($app) {

    die('ok');
    $fc = file_get_contents(__DIR__.'/api_response.txt');

    $arr = json_decode($fc);
    echo '<pre>';
    print_r($arr);

    die;
    $arr = array(
        'action'    => 'get_offers'
        ,'username' => 'travelexpert'
        ,'password' => '8Zmq9wnF'
    );

    $q =  http_build_query($arr);

    $request = new Buzz\Message\Form\FormRequest('POST', '/', 'http://www.christiantour.ro/reseller_api/index');
    $request->setFields($arr);
    $response = new Buzz\Message\Response();

    $client = new Buzz\Client\FileGetContents();
    $client->send($request, $response);

    if($response->isOk()) {
        $fh = fopen(__DIR__."/api_response.txt", 'w+');
        fwrite($fh, $response->getContent());
        fclose($fh);

        //$response->getContent();
    }

    die('?');
    echo '<pre>';
    print_r(get_class_methods($client));
    print_r(get_class_methods($request));
    print_r(get_class_methods($response));
    die;
    //echo $request;
    //echo $response; 
});

$app->run();
 */
