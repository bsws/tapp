<?php

namespace Travel\Util;

class DbMapper {
    static function getObjMap($providerName, $tableName) {
        switch($tableName) {
            case 'offers':

                switch($providerName) {
                    case 'christiantour':
                        return array(
                           'id'             =>  'offer_id'
                          ,'provider_id'    =>  null
                          ,'type'           =>  'type'
                          ,'search_id'      =>  'search_id'
                          ,'search_url'     =>  'search_url'
                          ,'result_id'      =>  'result_id'
                          ,'package_id'     =>  'package_id'
                          ,'circuit_id'     =>  'circuit_id'
                          ,'title'          =>  'offer_title'
                          ,'url'            =>  'offer_url'
                          ,'description'    =>  'offer_description'
                          ,'hotel_id'       =>  'hotel_id'
                          ,'attribute_id'   =>  'attribute_id'
                          ,'image_src'      =>  'image_src'
                          ,'image_alt'      =>  'image_alt'
                          ,'room_type'      =>  'room_type'
                          ,'meal_plan'      =>  'meal_plan'
                          ,'meal_plan_url'  =>  'meal_url'
                          ,'discount_info'  =>  'discount_info'
                          ,'price'          =>  'price'
                          ,'discount'       =>  'discount'
                          ,'hot_deal'       =>  'hot_deal'
                          ,'tax'            =>  'tax'
                          ,'status'         =>  'status'
                          ,'provider_created'        =>  'created'
                          ,'provider_modified'       =>  'modified'
                          ,'created'        => null
                          ,'min_price'      => 'min_price'
                          ,'imported_at'    => null
                          ,'modif_at'       => null
                        );
                    break;
                }

            break;
        }
    }

    static function getInsertFields($providerName, $tableName, $op = 'insert') {
        $allFields = self::getObjMap($providerName, $tableName);

        //if($op == 'insert') {
        //    $excludeFields = array();
        //}

        return $allFields;
    }
}
