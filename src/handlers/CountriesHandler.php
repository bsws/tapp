<?php
namespace Travel\Handlers;

class CountriesHandler {

    private $dbHandler;

    function __construct($dbHandler) {

        $this->dbHandler = $dbHandler;

    }

    function getDbHandler() {
        return $this->dbHandler;
    }

    function getBy($fields) {
        return $this->getDbHandler()->getBy($fields);
    }

    function sync($country) {

        return $this->getDbHandler()->sync($country);

    }
}
