<?php
namespace Travel\Handlers;

class CountriesHandler extends GenericHandler{

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

    function getAssoc() {
        $countriesArr = $this->getDbHandler()->findAll();

        $countries = array();

        foreach($countriesArr as $arr) {
            $countries[$arr['title']] = $arr['id'];
        }

        return $countries;
    }

    function updateCounters() {
        return $this->getDbHandler()->updateCounters();
    }
}
