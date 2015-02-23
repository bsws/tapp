<?php

namespace Travel\Util;

use Travel\Handlers\DataTable;

class Utils {
    static function getOffersGrid($router, $gridParams = array(), $gridExtraParams = array()) {
        $gridO = new DataTable(array(
                 'columns'      => array(
                    'TourOperator'   => array('sortable' => true)
                    ,'Titlu'   => array('sortable' => true)
                    ,'Tip'         => array('sortable' => true) 
                    ,'Destinatie'   => array('sortable' => true) //destination, country
                    ,'Sync @'   => array('sortable' => true)
                    ,'Activ'   => array('sortable' => true)
                    ,'&nbsp;'   => array('sortable' => false)
                 )
                ,'ajax_url'     =>  $router->generate('offers', $gridExtraParams)
                ,'table_title'  =>  $gridParams['pageTitle']
                ,'order'        =>  array(1, 'DESC')
            ));

        return $gridO;
    }
}
