<?php

namespace Travel\Handlers;

class TransportDbHandler {

     private $dbal
         ,$table = 'transport'
         ;

    function __construct($dbal) {

        $this->dbal = $dbal;

    }

    function getDbal() {
        return $this->dbal;
    }


    function sync($data) {

        $arr = array('url' => $data['url']);
        $dbObj = $this->findOneBy($arr);

        if(false === $dbObj)
            return $this->getDbal()->insert($this->table, $data);
        return $dbObj['id'];

    }

    function findOneBy($arr) {
        $sql = "SELECT * FROM {$this->table} WHERE 1 " ;

        foreach($arr as $k => $val) {
            $sql .= 'AND '.$k.' = ? ';
        }

        return $this->getDbal()->fetchAssoc($sql, array_values($arr));
    }
}
