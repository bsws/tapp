<?php

namespace Travel\Handlers;

class CountriesDbHandler extends GenericDbHandler {

     private 
         $table = 'countries'
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

    function findAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY title ASC";
        return $this->getDbal()->fetchAll($sql);
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

        $sWhere = $this->getListWhere($request, array(array('c', 'title'), array('c', 'url')));
        $sOrder = $this->getListOrder($request, array(array('c', 'title'), array('c', 'url'), null));

        $columns = "
              c.id
             ,c.title
             ,c.url
        ";

        $from = "
                countries c
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
            $row_arr[] = $res['title'];
            $row_arr[] = $res['url'];
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
                c.id
                ,c.title
                ,COUNT(DISTINCT poh.offer_id) hotels_no
                ,COUNT(DISTINCT poc.offer_id) circuits_no
                ,COUNT(DISTINCT pop.offer_id) packages_no
            FROM
                countries c
                LEFT JOIN destinations dh ON dh.country_id = c.id
                LEFT JOIN providers_offers poh ON poh.destination_id = dh.id AND poh.provider_id = 1 AND poh.type = 'hotel'

                LEFT JOIN destinations dc ON dc.country_id = c.id
                LEFT JOIN providers_offers poc ON poc.destination_id = dc.id AND poc.provider_id = 1 AND poc.type = 'circuit'

                LEFT JOIN destinations dp ON dp.country_id = c.id
                LEFT JOIN providers_offers pop ON pop.destination_id = dp.id AND pop.provider_id = 1 AND pop.type = 'package'

            GROUP BY c.id
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

