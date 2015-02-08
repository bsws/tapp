<?php

namespace Travel\Util;

class OffersHandler {

    private $dbHandler;

    function __construct($dbHandler) {

        $this->dbHandler = $dbHandler;

    }

    function getDbHandler() {
        return $this->dbHandler;
    }

    function parseJSONResponse($jsonStr) {

        return json_decode($jsonStr);

    }

    function syncronizeOffersForProvider($providerName, $app) {
        //for the moment, just parse the file with the JSON offers, and insert the offers in DB
        $objects = json_decode(file_get_contents($app[$providerName.'.json_offers_file']));
        $this->syncObjects($providerName, $objects);
    }

    function syncObjects($providerName, $objects) {
        return $this->getDbHandler()->syncronizeObjects($providerName, $objects);
    }
}
