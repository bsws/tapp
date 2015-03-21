<?php

namespace Travel\Handlers;

class HotelsDbHandler extends GenericDbHandler {

     private
         $table = 'hotels'
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

        $whereArr = array(
                         array('h', 'name')
                        ,array('d', 'title')
                        ,array('c', 'title')
                        ,array('p', 'name')
                        ,array('h', 'htype')
                        ,array('h', 'description')
                    );

        $orderArr = array(
                         array('h', 'name')
                        ,array('d', 'title')
                        ,array('c', 'title')
                        ,array('p', 'name')
                        ,array('h', 'htype')
                        ,array('h', 'stars')
                        ,null
                        ,null
                    );

        $sWhere = $this->getListWhere($request, $whereArr);
        $sOrder = $this->getListOrder($request, $orderArr);

        $columns = "
             h.name h_name
            ,h.htype h_type
            ,h.stars h_stars
            ,h.description h_description
            ,d.title d_title
            ,c.title c_title
            ,p.name p_name

        ";

        $from = "
            hotels h
            JOIN destinations d ON d.id = h.destination_id
            JOIN countries c ON c.id = d.country_id
            JOIN providers p ON p.id = h.provider_id
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
        //prd($results);

        $ret_arr = array();

        foreach ($results as $res)
        {

            $row_arr = array();
            $row_arr[] = $res['h_name'];
            $row_arr[] = $res['d_title'];
            $row_arr[] = $res['c_title'];
            $row_arr[] = $res['p_name'];
            $row_arr[] = $res['h_type'];
            $row_arr[] = $res['h_stars'];
            $row_arr[] = $res['h_description'];
            $row_arr[] = ' -- ';
            $ret_arr[] = $row_arr;

        }

        return array(
            'totalRowsFound' => $allRecs
            ,'results' => $ret_arr
        );
    }
}
