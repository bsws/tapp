<?php

namespace Travel\Handlers;

class ImagesDbHandler {

     private $dbal
         ,$table = 'offers_images'
         ;

    function __construct($dbal) {

        $this->dbal = $dbal;

    }

    function getDbal() {
        return $this->dbal;
    }


    function save($data) {
        return $this->getDbal()->insert($this->table, $data);
    }

    function delete($criteria) {
        return $this->getDbal()->delete($this->table, $criteria);
    }
}
