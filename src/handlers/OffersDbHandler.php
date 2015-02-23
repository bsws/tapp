<?php

namespace Travel\Handlers;

use Travel\Util\DbMapper;

class OffersDbHandler {

     private $dbal
         ,$table = 'providers_offers'
         ;

    function __construct($dbal) {

        $this->dbal = $dbal;

    }

    function getDbal() {
        return $this->dbal;
    }


    function sync($providerInfo, $object) {

        $now = date('Y-m-d H:i:s');

        $object->provider_id = $providerInfo['id'];
        $object->imported_at = $now;
        $object->modif_at = $now;

        return $this->save($providerInfo, $object);

    }

    function save($providerInfo, $object) {
        //try to get the object by id and provider_id
        $dbObj = $this->findOneBy(array('provider_id' => $object->provider_id, 'offer_id'  => $object->id));

        if(false === $dbObj) {
            //insertable fields
            $mapArr             = DbMapper::getInsertFields($providerInfo['ident'], 'offer');
            $translatedObject   = $this->translateObject($mapArr, $object);
            //prd($translatedObject);
            $this->getDbal()->insert($this->table, (array) $translatedObject);
        }
        else {
            //updatable fields
            $updatableFields    = DbMapper::getUpdatableFields('providers_offers');
            $translatedObject   = $this->translateObject($updatableFields, $object);

            //update
            $this->getDbal()->update($this->table, (array) $translatedObject, array('provider_id' => $object->provider_id, 'offer_id'  => $object->id));
        }
    }

    function findOneBy($arr) {
        $sql = "SELECT * FROM {$this->table} WHERE 1 " ;

        foreach($arr as $k => $val) {
            $sql .= 'AND '.$k.' = ? ';
        }

        return $this->getDbal()->fetchAssoc($sql, array_values($arr));
    }

    function syncronizeObjects($providerInfo, $objArray/* array of stdObjects */, $entity) {

        $tempConfigObj      = DbMapper::getConfigVars($entity);

        //insertable fields
        $mapArr             = DbMapper::getInsertFields($providerName, $entity);

        //updatable fields
        $updatableFields    = DbMapper::getUpdatableFields($tempConfigObj['tableName']);

        //information about the provider
        if(empty($extraVars['providerInfo'])) {
            $providerInfo       = $this->getProviderInfo($providerName);
        }
        else $providerInfo  = $extraVars['providerInfo'];

        $now = date('Y-m-d H:i:s');

        foreach($objArray as $item) { //bind the values one by one
            if($entity == 'image' AND empty($item->src)) continue;

            //set the provider id
            $item->provider_id = $providerInfo->id;

            if($entity == 'image') {
                $item->offer_id = $extraVars['offer_id'];
            }

            if($entity == 'offer') {
                //set imported @, modif @
                $item->imported_at = $now;
                $item->modif_at = $now;
            }

            $this->update($tempConfigObj['tableName'], $mapArr, $item, $updatableFields);

            if($entity == 'offer') {
                //try to sync country, destination, hotel
                $countryInfo = $this->getBy('countries', array('url' => $item->country_url));
                prdud($countryInfo);
                //$this->syncronizeObjects($providerName, $item->images, 'image', array('offer_id' => $item->id, 'providerInfo' => $providerInfo));
            }
        }

        //if($entity == 'image') {
        //    prdu($mapArr);
        //    prdu($updatableFields);
        //    prdud($objArray);
        //    die;
        //}

    }

    /**
     *update the offers row
     **/
    function update($tableName, $mapArr, $object, $updatableFields) {
        $q = "INSERT INTO {$tableName}(";
        $valsPart = "(";

        $duplPart = '';
        if(!empty($updatableFields)) {
            $duplPart = " ON DUPLICATE KEY UPDATE ";
        }

        foreach($mapArr as $tableField => $objField) {
            $q          .= "`$tableField`,";
            $valsPart   .= ":$tableField,";

            if(in_array($tableField, (array) $updatableFields)) {
                $duplPart   .= "`$tableField` = :$tableField,";
            }
        }

        $q          = rtrim($q, ',');
        $valsPart   = rtrim($valsPart, ',');
        $duplPart   = rtrim($duplPart, ',');

        $q          .= ")";
        $valsPart   .= ")";

        $q = $q.' VALUES '.$valsPart;
        if(!empty($duplPart)) $q .= $duplPart;

        $stmt = $this->getPDO()->prepare($q);

        foreach($mapArr as $tableField => $objField) {

            if(!empty($objField) AND isset($object->$objField)) {
                $val = $object->$objField;
            }
            else {
                $val = '';
            }

            $stmt->bindValue(':'.$tableField, $val);
        }

        return $stmt->execute();
    }

    function translateObject($mappedArr, $object) {

        $no = (object) null;

        foreach($mappedArr as $dbField => $objectField) $no->$dbField = isset($object->$objectField) ? $object->$objectField : '';

        return $no;

    }

    function getAllOfferIds($providerIdent) {
        $q = "SELECT 
                offer_id 
              FROM {$this->table} po
                  JOIN providers p ON po.provider_id = p.id
              WHERE 
                  p.ident = '$providerIdent'";
        return $ids = $this->getDbal()->fetchAll($q);
    }

    function markInactive($providerId, $ids) {
        $q = "UPDATE {$this->table} SET `active` = 0 WHERE provider_id = '$providerId' AND offer_id IN ('".implode('\',\'',array_keys($ids))."')";
        return $this->getDbal()->query($q);
    }

}
