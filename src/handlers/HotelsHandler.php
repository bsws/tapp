<?php
namespace Travel\Handlers;

class HotelsHandler {

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

    function sync($data) {

        return $this->getDbHandler()->sync($data);

    }
}
