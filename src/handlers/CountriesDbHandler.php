<?php

namespace Travel\Handlers;

class CountriesDbHandler {

     private $dbal
         ,$table = 'countries'
         ;

    function __construct($dbal) {

        $this->dbal = $dbal;

    }

    function getDbal() {
        return $this->dbal;
    }


    function sync($data) {

        $arr = array('url' => $data['url']);
        $dbCountry = $this->findOneBy($arr);

        if(false === $dbCountry)
            return $this->getDbal()->insert($this->table, $data);
        return $dbCountry['id'];

    }

    function findOneBy($arr) {
        $sql = "SELECT * FROM {$this->table} WHERE 1 " ;

        foreach($arr as $k => $val) {
            $sql .= 'AND '.$k.' = ? ';
        }

        return $this->getDbal()->fetchAssoc($sql, array_values($arr));
    }
}
