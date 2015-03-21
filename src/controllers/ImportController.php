<?php 
namespace Travel\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Travel\Util\Syncronizer;

class ImportController {

    /**
     *  import / sync offers - starting with Christian tour
     **/
    public function importOffers(Request $request, Application $app) {

        $providerInfo = $this->getProviderInfo($request, $app);
        Syncronizer::syncOffers($providerInfo, $app);

        return 'Offers for '.$providerInfo['name'].' - Ok';
    }

    public function updateCounters(Request $request, Application $app) {

        Syncronizer::updateCounters($app);
        return "Counters are syncronized.";

    }

    public function importDestinations(Request $request, Application $app) {

        $providerInfo = $this->getProviderInfo($request, $app);

        Syncronizer::syncCountries($providerInfo, $app);
        Syncronizer::syncDestinations($providerInfo, $app);

        return 'Destinations for '.$providerInfo['name'].' - Ok';
    }

    function getProviderInfo(Request $request, Application $app) {

        $providerIdent = $request->get('provider_ident');
        $providerInfo  = $app['providerHandler']->findOneByIdent($providerIdent);

        return $providerInfo;
    }

}
