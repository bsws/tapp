<?php 
namespace Travel\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OffersController {

    public function index(Request $request, Application $app) {

        if($request->isXMLHttpRequest()) {

            $data = $app['offersHandler']->getForList($request);

            //$response = new Response();
            //$response->headers->set('Content-Type', 'application/json');

            $response = new Response();
            $response->headers->set('Content-Type', 'application/json');

            $json = json_encode(array('sEcho' => $request->get('sEcho'), 'iTotalRecords' => $data['totalRowsFound'], 'iTotalDisplayRecords' => $data['totalRowsFound'], 'aaData' => $data['results']));
            $response->setContent($json);

            return $response;
            //$response->setContent($json);

            //return $response;

            //foreach($data['results'] as $pos => $arr){
            //    $invoiceArr = explode('--', $arr[3]);
            //    $invoiceId  = $invoiceArr[0];
            //    $data['results'][$pos][3] = trim($invoiceArr[1]);
            //    $detailsLnk = '<a href="'.$this->generateUrl('invoice_receipt_edit', array('bookingId'=> $arr[7], 'invoiceId' => $invoiceId, 'receiptId' => $arr[6])).'" class="btn btn-default btn-xs">Detalii</a>&nbsp;&nbsp;<a href="'.$this->generateUrl('receipts_download', array('receiptId' => $arr[6])).'" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-download-alt"></i></a>';
            //    $data['results'][$pos][6] = $detailsLnk;
            //}

        }

        $gridO = $app['utils']->getOffersGrid(
            $app['url_generator'],
            array('pageTitle'   => 'Oferte')
        );

        return $app['twig']->render('admin/offers.html', array(
            'gridO' => $gridO
        ));
    }

}
