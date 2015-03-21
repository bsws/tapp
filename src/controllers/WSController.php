<?php

namespace Travel\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WSController {

    public function suggestions(Request $request, Application $app) {

        $dbData = $app['offersHandler']->getSuggestionsForSearch($request->get('t', ''), $request->get('start', 0), $request->get('offset', 15));
        return json_encode(array('data' => $dbData));

    }

    function results(Request $request, Application $app) {

        $dbData = $app['offersHandler']->getSearchResults($request->get('ty', '---'), $request->get('url', '---'), $request->get('start', 0), $request->get('offset', 15));
        return json_encode(array('data' => $dbData));

    }

}
