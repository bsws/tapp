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
}
