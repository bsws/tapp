<?php
namespace Travel\Util;

use Buzz\Message\Form\FormRequest;
use Buzz\Message\Response;
use Buzz\Client\FileGetContents;

class DataSource {

    /**
     *  download or get from file the offers
     **/
    static function provide($providerInfo, $app, $items = 'offers') {

        switch($items) {

            case 'offers': 
                //return self::getOffersFromFile($providerInfo['ident'], $app);
                return self::downloadCurrentOffers($providerInfo['ident'], $app);
            break;
            case 'destinations': 

                $filePath = $app[$providerInfo['ident'].'.json_destinations_file'];
                return self::getFileContent($filePath);
                return self::downloadCurrentDestinations($providerInfo['ident'], $app);
            break;
        }
    }

    static function downloadCurrentDestinations($providerIdent, $app) {
        $arr = array(
             'action'    => 'get_geography'
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
            echo $response->getContent();
            die;
            return json_decode($response->getContent());
        }
        else {
            echo "The response receiver interrogating the provider {$providerIdent} is not ok. (".__FILE__." ".__METHOD__." ".__LINE__.")";
            die;
        }
    }

    //to made further changes
    static function downloadCurrentOffers($providerIdent, $app) {

        //
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

            $responseContent = $response->getContent();
            //write the response
            $tempDir = __DIR__.'/../../temp';
            $h = fopen($tempDir.'/'.date('YmdHis').'.txt', 'w');
            if($h) {
                $writed = fwrite($h, $responseContent);
                fclose($h);
            }

            return json_decode($responseContent);
        }
        else {
            echo "The response receiver interrogating the provider {$providerIdent} is not ok. (".__FILE__." ".__METHOD__." ".__LINE__.")";
            die;
        }
    }

    static function getOffersFromFile($providerIdent, $app) {
        $objects = json_decode(file_get_contents($app[$providerIdent.'.json_offers_file']));
        return $objects;
    }

    static function getDestinationsFromFile($providerIdent, $app) {
        $objects = json_decode(file_get_contents($app[$providerIdent.'.json_offers_file']));
        return $objects;
    }

    static function getFileContent($filePath) {
        $objects = json_decode(file_get_contents($filePath));
        return $objects;
    }

}
