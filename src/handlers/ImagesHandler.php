<?php
namespace Travel\Handlers;

class ImagesHandler {

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

    function save($data) {

        return $this->getDbHandler()->save($data);

    }

    function delete($criteria) {
        return $this->getDbHandler()->delete($criteria);
    }
}
