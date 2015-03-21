<?php
namespace Travel\Handlers;

class ProviderDbHandler extends GenericDbHandler {

    private
        $table = 'providers'
    ;

    function __construct($dbal) {
        $this->dbal = $dbal;
    }

    function findOneByIdent($ident) {
        $sql = "SELECT * FROM {$this->table} WHERE ident = ? " ;
        return $this->dbal->fetchAssoc($sql, array($ident));
    }

    public function getForList($request)
    {
        $limitStart = $request->get('iDisplayStart') ? $request->get('iDisplayStart') : 0;
        $limitLength = $request->get('iDisplayLength') ? $request->get('iDisplayLength') : 12;

        if($limitLength < 0) $limitLength = 10;

        $sWhere = $this->getListWhere($request, array(
                         array('p', 'ident')
                        ,array('p', 'name')
                    ));
        $sOrder = $this->getListOrder($request, array(array('p', 'name'), array('p', 'ident'), null));

        $columns = "
              p.ident
             ,p.name
        ";

        $from = "
                providers p
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
            $row_arr[] = $res['ident'];
            $row_arr[] = ' -- ';
            $ret_arr[] = $row_arr;

        }

        return array(
            'totalRowsFound' => $allRecs
            ,'results' => $ret_arr
        );
    }
}
