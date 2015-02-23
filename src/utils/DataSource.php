<?php
namespace Travel\Util;

use Buzz\Message\Form\FormRequest;
use Buzz\Message\Response;
use Buzz\Client\FileGetContents;

class DataSource {

    static function provide($providerInfo, $app) {
        //return self::getFromFile($providerInfo['ident'], $app);
        return self::downloadCurrentOffers($providerInfo['ident'], $app);
    }

    //to made further changes
    static function downloadCurrentOffers($providerIdent, $app) {

        //query the api
        $arr = array(
             'action'    => 'get_offers'
            ,'username' => $app[$providerIdent.'.api_user']
            ,'password' => $app[$providerIdent.'.api_password']
        );

        $q =  http_build_query($arr);

        $request = new FormRequest($app[$providerIdent.'.api_request_method'], '/', $app[$providerIdent.'.api_url']);
        $request->setFields($arr);

        $response = new Response();

        $client = new FileGetContents();
        $client->send($request, $response);

        if($response->isOk()) {
            return json_decode($response->getContent());
        }
        else {
            echo "The response receiver interrogating the provider {$providerIdent} is not ok. (".__FILE__." ".__METHOD__." ".__LINE__.")";
            die;
        }
    }

    static function getFromFile($providerIdent, $app) {
        $objects = json_decode(file_get_contents($app[$providerIdent.'.json_offers_file']));
        return $objects;
    }

}
