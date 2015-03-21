<?php

namespace Travel\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DestinationsController {

    public function index(Request $request, Application $app) {

        if($request->isXMLHttpRequest()) {

            $data = $app['destinationsHandler']->getForList($request);

            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');

            $json = json_encode(array('sEcho' => $request->get('sEcho'), 'iTotalRecords' => $data['totalRowsFound'], 'iTotalDisplayRecords' => $data['totalRowsFound'], 'aaData' => $data['results']));
            $response->setContent($json);

            return $response;

        }

        $gridO = $app['utils']->getDestinationsGrid(
            $app['url_generator'],
            array('pageTitle'   => 'Destinatii')
        );

        return $app['twig']->render('admin/generic/list.html', array(
            'gridO' => $gridO
        ));

    }
}
