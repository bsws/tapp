<?php 
namespace Travel\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Travel\Util\Syncronizer;

class ImportController {

    public function import(Request $request, Application $app) {
        $providerIdent = $request->get('provider_ident');
        $providerInfo  = $app['providerHandler']->findOneByIdent($providerIdent);

        Syncronizer::syncOffers($providerInfo, $app);
        //get the offers handler service
        //$app['offersHandler']->syncronizeOffersForProvider($providerInfo, $app);

        return 'Ok';
    }

}
