<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Buzz\Message\Form\FormRequest;
use Buzz\Message\Response as BR;
use Buzz\Client\FileGetContents;

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html', array());
})
->bind('homepage')
;

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login/form.html', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username')
    ));
});


$app->get('/offer-details/{url}/{search_url}/{result_id}/{type}/', function($url, $search_url, $result_id, $type) use($app) {
    $providerIdent = 'christiantour';
    $arr = array(
         'action'    => 'get_view'
        ,'username' => $app[$providerIdent.'.api_user']
        ,'password' => $app[$providerIdent.'.api_password']

        ,'url'          => $url
        ,'search_url'   => $search_url 
        ,'result_id'    => $result_id 
        ,'type'         => $type
    );

    $q =  http_build_query($arr);

    $request = new FormRequest($app[$providerIdent.'.api_request_method'], '/', $app[$providerIdent.'.api_url']);
    $request->setFields($arr);

    $response = new BR();

    $client = new FileGetContents();
    $client->send($request, $response);

    if($response->isOk()) {
        $resp = json_decode($response->getContent());
        prd($resp);
    }
    else {
        echo "The response receiver interrogating the provider {$providerIdent} is not ok. (".__FILE__." ".__METHOD__." ".__LINE__.")";
        die;
    }
});


$app->get('/admin/', 'controller.admin:dashboard');
$app->get('/import/{provider_ident}', 'controller.import:import');
