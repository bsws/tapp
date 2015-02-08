<?php 
namespace Travel\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ImportController {

    public function import(Request $request, Application $app) {
        $provider = $request->get('provider_name');

        //get the offers handler service
        $app['offersHandler']->syncronizeOffersForProvider($provider, $app);

        return 'Ok';
    }

}
