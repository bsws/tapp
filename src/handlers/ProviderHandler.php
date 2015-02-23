<?php

namespace Travel\Handlers;

class ProviderHandler {

    private $dbHandler;

    function __construct($dbHandler) {

        $this->dbHandler = $dbHandler;

    }

    function getDbHandler() {
        return $this->dbHandler;
    }

    function findOneByIdent($ident) {
        return $this->getDbHandler()->findOneByIdent($ident);
    }

}
