<?php

namespace Travel\Handlers;

class DestinationsDbHandler extends GenericDbHandler {

     private
         $table = 'destinations'
         ;

    function __construct($dbal) {

        $this->dbal = $dbal;

    }

    function getDbal() {
        return $this->dbal;
    }


    function sync($data, $full = false) {

        $arr = array('url' => $data['url']);
        $dbObj = $this->findOneBy($arr);

        if(false === $dbObj)
            return $this->getDbal()->insert($this->table, $data);

        if(
            $data['title'] != $dbObj['title']
            //OR $data['url'] != $dbObj['url']
            OR $data['visible'] != $dbObj['visible']
            OR $data['country_id'] != $dbObj['country_id']
        )
        {
            $this->getDbal()->update('destinations', $data, array('id' => $dbObj['id']));
        }

        //if(substr($dbObj['title'], 0, 5) == 'Creta')
        //    pr($dbObj);
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
                         array('d', 'title')
                        ,array('d', 'url')
                        ,array('c', 'title')
                    );

        $orderArr = array(
                         array('d', 'title')
                        ,array('d', 'url')
                        ,array('d', 'visible')
                        ,array('c', 'title')
                        ,null
                    );

        $sWhere = $this->getListWhere($request, $whereArr);
        $sOrder = $this->getListOrder($request, $orderArr);

        $columns = "
              d.title
             ,d.url
             ,d.visible
             ,c.title country
        ";

        $from = "
            destinations d
            JOIN countries c ON c.id = d.country_id
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
            $row_arr[] = $res['title'];
            $row_arr[] = $res['country'];
            $row_arr[] = $res['url'];
            $row_arr[] = !empty($res['visible']) ? 'DA' : 'NU';
            $row_arr[] = ' -- ';
            $ret_arr[] = $row_arr;

        }

        return array(
            'totalRowsFound' => $allRecs
            ,'results' => $ret_arr
        );
    }

    function updateCounters() {

        //get counters set
        $q = "
            SELECT 
                d.id
                ,d.title
                ,COUNT(DISTINCT poh.offer_id) hotels_no
                ,COUNT(DISTINCT poc.offer_id) circuits_no
                ,COUNT(DISTINCT pop.offer_id) packages_no
            FROM
                destinations d

                LEFT JOIN providers_offers poh ON poh.destination_id = d.id AND poh.provider_id = 1 AND poh.type = 'hotel'
                LEFT JOIN providers_offers poc ON poc.destination_id = d.id AND poc.provider_id = 1 AND poc.type = 'circuit'
                LEFT JOIN providers_offers pop ON pop.destination_id = d.id AND pop.provider_id = 1 AND pop.type = 'package'

            GROUP BY d.id
        ";

        $stmt = $this->getDbal()->prepare($q);
        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $counters = $stmt->fetchAll();

        foreach($counters as $cArr) {

            $cId = $cArr['id'];
            unset($cArr['id']);
            unset($cArr['title']);

            $this->getDbal()->update($this->table, $cArr, array('id' => $cId));
        }

        return ;
    }
}
