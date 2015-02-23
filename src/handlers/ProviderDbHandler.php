<?php
namespace Travel\Handlers;

class ProviderDbHandler {

    private $dbal
        ,$table = 'providers'
    ;

    function __construct($dbal) {
        $this->dbal = $dbal;
    }

    function findOneByIdent($ident) {
        $sql = "SELECT * FROM {$this->table} WHERE ident = ? " ;
        return $this->dbal->fetchAssoc($sql, array($ident));
    }

}
