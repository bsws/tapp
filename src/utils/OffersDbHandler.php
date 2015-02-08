<?php

namespace Travel\Util;

class OffersDbHandler {

    private $pdo;

    private $offersTable = 'providers_offers';

    function __construct($pdo) {

        $this->pdo = $pdo;

    }

    function getProviderInfo($providerIdent) {
        $dbh = $this->pdo->prepare("SELECT * FROM providers WHERE `ident` = '$providerIdent' LIMIT 1");
        $dbh->execute();

        $provider = $dbh->fetchObject();

        return $provider;
    }


    function syncronizeObjects($providerName, $objArray/* array of stdObjects */) {

        $mapArr = DbMapper::getInsertFields($providerName, 'offers');
        $providerInfo = $this->getProviderInfo($providerName);

        $providerTableFields = array_keys($mapArr);

        $fieldsStr = '`'.implode('`,`', $providerTableFields).'`';
        $query = "INSERT INTO {$this->offersTable} ($fieldsStr) VALUES "; //Prequery

        $paramsQM = array_fill(0, count($providerTableFields), '?');

        $qPart = array_fill(0, count($objArray), '('.implode(',', $paramsQM).')');

        $query .=  implode(",",$qPart);

        $stmt = $this->pdo->prepare($query); 


        $now = date('Y-m-d H:i:s');

        $i = 1;
        //prd($mapArr);
        foreach($objArray as $item) { //bind the values one by one

            foreach($mapArr as $tField => $oField) {
                if(!is_null($oField)) {
                    if(isset($item->$oField)) {
                    echo $oField,': ',$item->$oField,"<br />";
                        $stmt->bindParam($i++, $item->$oField);
                    } else {
                    echo 'trec pentru ',$oField,"<br />";
                        $i ++;
                    }
                }
                else {
                    if($tField == 'provider_id') $val = $providerInfo->id;
                    if($tField == 'created') $val = $now;
                    if($tField == 'imported_at') $val = $now;
                    if($tField == 'modif_at') $val = $now;

                    if(isset($val)) {
                        echo $tField,': ',$val,"<br />";
                        $stmt->bindParam($i++, $val);
                    }
                }
            }

        }
        $stmt->execute();
        die('ar trebui');
    }



}
