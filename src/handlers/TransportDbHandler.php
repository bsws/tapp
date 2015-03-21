<?php

namespace Travel\Handlers;

class TransportDbHandler extends GenericDbHandler {

     private
         $table = 'transport'
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

        if(false === $dbObj) {
            $this->getDbal()->insert($this->table, $data);
            return $this->getDbal()->lastInsertId();
        }
        return $dbObj['id'];

    }

    function findOneBy($arr) {
        $sql = "SELECT * FROM {$this->table} WHERE 1 " ;

        foreach($arr as $k => $val) {
            $sql .= 'AND '.$k.' = ? ';
        }

        return $this->getDbal()->fetchAssoc($sql, array_values($arr));
    }

    public function getForList($request)
    {
        $limitStart = $request->get('iDisplayStart') ? $request->get('iDisplayStart') : 0;
        $limitLength = $request->get('iDisplayLength') ? $request->get('iDisplayLength') : 12;

        if($limitLength < 0) $limitLength = 10;

        $sWhere = $this->getListWhere($request, array(
                         array('t', 'url')
                        ,array('t', 'name')
                    ));

        $sOrder = $this->getListOrder($request, array(array('t', 'name'), null));

        $columns = "
             t.name
        ";

        $from = "
            transport t
        ";

        $q = "SELECT
                $columns
              FROM 
                $from
                $sWhere ";
        $q .= " 
                $sOrder
                LIMIT $limitStart, $limitLength
              ";

        $cQ = "SELECT
                COUNT(*)
              FROM 
                $from
                $sWhere 
              ";

        $stmt = $this->getDbal()->prepare($cQ);
        $stmt->execute();
        $allRecs  = $stmt->fetchColumn(0);

        $stmt = $this->getDbal()->prepare($q);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $results = $stmt->fetchAll();

        $ret_arr = array();

        foreach ($results as $res)
        {

            $row_arr = array();
            $row_arr[] = $res['name'];
            $row_arr[] = ' -- ';
            $ret_arr[] = $row_arr;

        }

        return array(
            'totalRowsFound' => $allRecs
            ,'results' => $ret_arr
        );
    }
}
