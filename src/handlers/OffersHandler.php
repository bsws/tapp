<?php

namespace Travel\Handlers;

class OffersHandler {

    private $dbHandler;

    function __construct($dbHandler) {

        $this->dbHandler = $dbHandler;

    }

    function getDbHandler() {
        return $this->dbHandler;
    }

    /**
     * syncronize a single object
     */
    function sync($providerInfo, $object) {
        //sync offer
        $this->getDbHandler()->sync($providerInfo, $object);
    }

    function getAllOfferIdsForProvider($providerIdent) {
        $dbIds = $this->getDbHandler()->getAllOfferIds($providerIdent);
        $ret = array();

        foreach($dbIds as $arr) {
            $ret[$arr['offer_id']] = 1;
        }

        return $ret;
    }

    function markInactive($providerId, $ids) {
        if(empty($ids)) return;
        return $this->getDbHandler()->markInactive($providerId, $ids);
    }

    function getForList($request) {
        $dbData = $this->getDbHandler()->getForList($request);
        return $dbData;
    }

    function getSuggestionsForSearch($term = '', $start = 0, $offset = 10) {

        $retArr = array();

        if(!empty($term)) {
            $dbRecs = $this->getDbHandler()->getSuggestionsForSearch($term, $start, $offset);

            foreach($dbRecs as $arr) {

                //$iconClass = self::getIconClass($arr['po_type']);


                $tempArr     = array('ti' => strlen($arr['title']) > 60 ? substr($arr['title'], 0, 60).'...' : $arr['title'], 'dt' => date('Y-m-d H:i:s'), 'ty' => $arr['type'], 'url' => $arr['url']);
                $hotelsStr   = $arr['hotels_no'] < 1 ? '' : '<strong>'.$arr['hotels_no'].'</strong>'.($arr['hotels_no'] == 1 ? ' hotel' : ' hoteluri');
                $circuitsStr = $arr['circuits_no'] < 1 ? '' : '<strong>'.$arr['circuits_no'].'</strong>'.($arr['circuits_no'] == 1 ? ' circuit' : ' circuite');
                $packagesStr = $arr['packages_no'] < 1 ? '' : '<strong>'.$arr['packages_no'].'</strong>'.($arr['packages_no'] == 1 ? ' sejur' : ' sejururi');
                $tempArr['ti'] = str_replace($term, '<strong>'.$term.'</strong>', $tempArr['ti']);

                if($arr['type'] == 'country') 
                    $tempArr['st'] = 'Tara';

                if($arr['type'] == 'dest') 
                    $tempArr['st'] = 'Destinatie';

                if(in_array($arr['type'], array('country', 'dest'))) {
                    //$tempArr['st'] .= ' - '.$hotelsStr.', '.$circuitsStr.', '.$packagesStr;
                    if($hotelsStr != '' OR $circuitsStr != '' OR $packagesStr != '') {

                        $tempArr['st'] .= ' - ';

                        if($hotelsStr != '') $tempArr['st'] .= $hotelsStr;
                        if($circuitsStr != '') $tempArr['st'] .= (($hotelsStr == '') ? '' : ', ').$circuitsStr;
                        if($packagesStr != '') $tempArr['st'] .= (($hotelsStr == '' AND $circuitsStr == '') ? '' : ', ').$packagesStr;
                    }
                    else {
                        $tempArr['st'] .= ' - Nici un hotel';
                    }
                    $tempArr['po'] = 1;//positiion
                    $retArr[] = $tempArr;
                }

                if(in_array($arr['type'], array('circuit', 'sejur'))) {
                    $tempArr['st'] = $arr['descr'];
                    $tempArr['po'] = 2;//position
                    $retArr[] = $tempArr;
                }


            }
        }

        return $retArr;

    }

    function getSearchResults($type, $url, $start = 0, $offset = 0) {

        $retArr = array();

        switch($type) {

            case 'circuit':
            case 'hotel':
            case 'sejur':

                $selectCols = array(
                     'search_id'
                    ,'search_url'
                    ,'result_id'
                    ,'title'
                    ,'url'
                    ,'type'
                    ,'image_src'
                    ,'image_alt'
                    ,'room_type'
                    ,'meal_plan'
                    ,'price'
                    ,'discount'
                    ,'hot_deal'
                    ,'status'
                    ,'min_price'
                );

                $res = $this->getDbHandler()->findOneBy(array('url' => $url), $selectCols);
                $retArr[] = $res;
            break;
            case 'hotel_offers':
                $retArr = $this->getDbHandler()->findOffersBy(array('hotel_url' => $url));
            break;
            case 'dest':
            case 'country':
                $selectCols = array(
                     'search_id'
                    ,'search_url'
                    ,'result_id'
                    ,'title'
                    ,'url'
                    ,'image_src'
                    ,'image_alt'
                    ,'room_type'
                    ,'meal_plan'
                    ,'price'
                    ,'discount'
                    ,'hot_deal'
                    ,'status'
                    ,'min_price'
                );

                $res = $this->getDbHandler()->findBy(array('type' => $type, 'url' => $url), $selectCols);

                foreach($res as $r) $retArr[] = $r;
            break;
        }

        return $retArr;

    }

    static function getIconClass($str) {

        switch($str) {
            case 'avion':
                $class = 'plane rot45';
            break;
            case 'hotel':
                $class = 'home';
            break;
            default:
                $class = 'star-empty';
            break;
        }
        return $class;

    }
}
