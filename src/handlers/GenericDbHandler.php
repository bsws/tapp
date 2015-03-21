<?php
namespace Travel\Handlers;

abstract class GenericDbHandler { 

    protected $dbal;

    function getDbal() {
        return $this->dbal;
    }

    public function getListOrder($request, $sColumns = array())
    {
        /**
         *  sortable columns in order
         */
        $sOrder = '';
        if(/*$request->get('iSortCol_0') AND */$request->get('sSortDir_0'))
        {
            $sOrder = "ORDER BY  ";
            for ( $i=0 ; $i<intval( $request->get('iSortingCols') ) ; $i++ )
            {
                if ( $request->get('bSortable_'.intval($request->get('iSortCol_'.$i)) ) == "true" )
                {
                    if(count($sColumns[ intval( $request->get('iSortCol_'.$i) ) ]) > 1)
                    {
                        $sOrder .= (empty($sColumns[ intval( $request->get('iSortCol_'.$i) ) ][0]) ? "" : $sColumns[ intval( $request->get('iSortCol_'.$i) ) ][0].".").$sColumns[ intval( $request->get('iSortCol_'.$i) ) ][1]." ". $request->get('sSortDir_'.$i ) .", ";
                    }
                    else{
                        $sOrder .= $sColumns[ intval( $request->get('iSortCol_'.$i) ) ][0]." ".
                            $request->get('sSortDir_'.$i ) .", ";
                    }
                }
            }

            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" )
            {
                $sOrder = "";
            }
        }
        return $sOrder;
    }

    function getListWhere($request, $columns)
    {
        /**
         *  columns where we search
         */
        $sWhere = 'WHERE 1 ';

        if($request->get('sSearch'))
        {
            $sWhere .= "AND (";

            $cCounter = count($columns);

            for ( $i=0 ; $i < $cCounter ; $i++ )
            {
                $sWhere .= $columns[$i][0].".".$columns[$i][1]." LIKE '%".$request->get('sSearch') ."%' OR ";
            }
            $sWhere = substr_replace( $sWhere, "", -3 );
            $sWhere .= ') ';
        }
        return $sWhere;
    }

}
