<?php

namespace Travel\Util;

class DbMapper {

    static function getObjMap($providerName, $tableName) {

        switch($tableName) {
            case 'offer':
                switch($providerName) {
                    case 'christiantour':
                        return array(
                           'offer_id'       =>  'id'
                          ,'provider_id'    =>  'provider_id'
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
                          ,'meal_plan_url'  =>  'meal_plan_url'
                          ,'discount_info'  =>  'discount_info'
                          ,'price'          =>  'price'
                          ,'discount'       =>  'discount'
                          ,'hot_deal'       =>  'hot_deal'
                          ,'tax'            =>  'tax'
                          ,'status'         =>  'status'
                          ,'provider_created'        =>  'created'
                          ,'provider_modified'       =>  'modified'
                          ,'min_price'      => 'min_price'
                          ,'imported_at'    => 'imported_at'
                          ,'modif_at'       => 'modif_at'
                          ,'attribute_id'   => ''
                          ,'active'   => ''
                        );
                    break;
                }

            break;

            case 'image':
                return array(
                    'provider_id' =>  'provider_id'
                   ,'offer_id'    =>  'offer_id'
                   ,'src'    =>  'src'
                   ,'alt'    =>  'alt'
                );
            break;
        }
    }

    static function getInsertFields($providerName, $tableName, $op = 'insert') {
        $allFields = self::getObjMap($providerName, $tableName);
        return $allFields;
    }

    static function getUpdatableFields($tableName) {
        switch($tableName) {
            case 'providers_offers': 
                return array(
                     'offer_id'         => 'id'
                    ,'search_id'        => 'search_id'
                    ,'search_url'       => 'search_url'
                    ,'result_id'        => 'result_id'
                    ,'title'            => 'offer_title'
                    ,'url'              => 'offer_url'
                    ,'description'      => 'description'
                    ,'hotel_id'         => 'hotel_id'
                    ,'attribute_id'     => 'attribute_id'
                    ,'image_src'        => 'image_src'
                    ,'image_alt'        => 'image_alt'
                    ,'room_type'        => 'room_type'
                    ,'meal_plan'        => 'meal_plan'
                    ,'meal_plan_url'    => 'meal_plan_url'
                    ,'discount_info'    => 'discount_info'
                    ,'price'            => 'price'
                    ,'discount'         => 'discount'
                    ,'hot_deal'         => 'hot_deal'
                    ,'tax'              => 'tax'
                    ,'status'           => 'status'
                    ,'provider_modified'=> 'provider_modified'
                    ,'modif_at'         => 'modif_at'
                    ,'destination_id'   => 'destination_id'
                    ,'active'           => 'active'
                );
            break;

            default:
                return array();
            break;
        }
    }

    static function getConfigVars($entity) {

        switch($entity) {
            case 'offer':
                $tableName = 'providers_offers';
            break;
            case 'image':
                $tableName = 'offers_images';
            break;
        }

        return array(
            'tableName' => $tableName
        );
    }
}
