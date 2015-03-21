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

    static function getProvidersGrid($router, $gridParams = array(), $gridExtraParams = array()) {
        $gridO = new DataTable(array(
                 'columns'          => array(
                    'Tour Operator'  => array('sortable' => true)
                    ,'Identificator' => array('sortable' => true)
                    ,'&nbsp;'       => array('sortable' => false)
                 )
                ,'ajax_url'     =>  $router->generate('providers', $gridExtraParams)
                ,'table_title'  =>  $gridParams['pageTitle']
                ,'order'        =>  array(1, 'DESC')
            ));

        return $gridO;
    }

    static function getCountriesGrid($router, $gridParams = array(), $gridExtraParams = array()) {
        $gridO = new DataTable(array(
                 'columns'          => array(
                    'Denumire'  => array('sortable' => true)
                    ,'URL'      => array('sortable' => true)
                    ,'&nbsp;'   => array('sortable' => false)
                 )
                ,'ajax_url'     =>  $router->generate('countries', $gridExtraParams)
                ,'table_title'  =>  $gridParams['pageTitle']
                ,'order'        =>  array(1, 'DESC')
            ));

        return $gridO;
    }

    static function getDestinationsGrid($router, $gridParams = array(), $gridExtraParams = array()) {
        $gridO = new DataTable(array(
                 'columns'      => array(
                    'Nume'      => array('sortable' => true)
                    ,'Tara'     => array('sortable' => true)
                    ,'URL'      => array('sortable' => true)
                    ,'Vizibil'  => array('sortable' => true)
                    ,'&nbsp;'   => array('sortable' => false)
                 )
                ,'ajax_url'     =>  $router->generate('destinations', $gridExtraParams)
                ,'table_title'  =>  $gridParams['pageTitle']
                ,'order'        =>  array(1, 'DESC')
            ));

        return $gridO;
    }

    static function getHotelsGrid($router, $gridParams = array(), $gridExtraParams = array()) {
        $gridO = new DataTable(array(
                 'columns'      => array(
                    'Nume'          => array('sortable' => true)
                    ,'Destinatie'   => array('sortable' => true)
                    ,'Tara'         => array('sortable' => true)
                    ,'Provider'     => array('sortable' => true)
                    ,'Tip'          => array('sortable' => true)
                    ,'Stele'        => array('sortable' => true)
                    ,'Scurta descriere'  => array('sortable' => false)
                    ,'&nbsp;'       => array('sortable' => false)
                 )
                ,'ajax_url'     =>  $router->generate('hotels', $gridExtraParams)
                ,'table_title'  =>  $gridParams['pageTitle']
                ,'order'        =>  array(1, 'DESC')
            ));

        return $gridO;
    }

    static function getTransportGrid($router, $gridParams = array(), $gridExtraParams = array()) {
        $gridO = new DataTable(array(
                 'columns'      => array(
                    'Nume'          => array('sortable' => true)
                    ,'&nbsp;'       => array('sortable' => false)
                 )
                ,'ajax_url'     =>  $router->generate('transport', $gridExtraParams)
                ,'table_title'  =>  $gridParams['pageTitle']
                ,'order'        =>  array(1, 'DESC')
            ));

        return $gridO;
    }

    static function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
}
