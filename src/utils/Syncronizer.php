<?php 
namespace Travel\Util;

class Syncronizer {

    static function syncCountries($providerInfo, $app) {
        //download or get objects from a file
        $objectsToSync = DataSource::provide($providerInfo, $app, 'destinations');

        foreach($objectsToSync as $objToSync) {
            $objToSync = array( 'url' => Utils::slugify($objToSync->country), 'title' => $objToSync->country );
            $app['countriesHandler']->sync($objToSync, true);
        }
    }

    static function syncDestinations($providerInfo, $app) {
        //download or get objects from a file
        $objectsToSync = DataSource::provide($providerInfo, $app, 'destinations');

        //get the countries from DB
        $countriesAssoc = $app['countriesHandler']->getAssoc();
        foreach($objectsToSync as $objToSync) {
            $objToSync->local_country_id = $countriesAssoc[$objToSync->country];
            $app['destinationsHandler']->sync($objToSync, true);
        }
    }

    static function syncOffers($providerInfo, $app) {

        //download or get objects from a file
        $objectsToSync = DataSource::provide($providerInfo, $app);

        $arr = array();
        foreach($objectsToSync as $o) {
            if(!isset($arr[$o->type])) {
                $arr[$o->type] = 1;
            }
        }

        //get the ids of db offers for current provider;
        $allIds = $app['offersHandler']->getAllOfferIdsForProvider($providerInfo['ident']);
        //die('ok');

        //update the 
        foreach($objectsToSync as $objToSync) {
            //echo $objToSync->id,'|',intval($objToSync->id) == 78,'|';
            //echo '<br />';
            //country
            if(!empty($objToSync->country_title)) {
                $country = array();
                $country['title'] = $objToSync->country_title;
                $country['url'] = $objToSync->country_url;
                $dbCountryId = $app['countriesHandler']->sync($country);

                $objToSync->country_id = $dbCountryId;
            }
            else $objToSync->country_id = 0;



            //destination
            if(!empty($objToSync->destination_title)) {
                $destination = array();
                $destination['title']       = substr($objToSync->destination_title, 0, strpos($objToSync->destination_title, ','));
                $destination['url']         = $objToSync->destination_url;
                $destination['visible']     = $objToSync->destination_visible;
                $destination['country_id']  = $objToSync->country_id;

                $dbDestId = $app['destinationsHandler']->sync($destination);
                $objToSync->destination_id = $dbDestId;
            }
            else {
                $objToSync->destination_id = 0;
                //prd($objToSync);
            }

            //hotel
            if(!empty($objToSync->hotel_name)) {
                $hotel = array();
                $hotel['provider_id']  = $providerInfo['id'] ;
                $hotel['id_at_provider']  = $objToSync->hotel_id ;
                $hotel['htype']  = empty($objToSync->hotel_type) ? '' : $objToSync->hotel_type ;
                $hotel['name']  = $objToSync->hotel_name ;
                $hotel['url']  = $objToSync->hotel_url ;
                $hotel['description']  = $objToSync->hotel_description ;
                $hotel['stars']  = $objToSync->hotel_stars ;
                $hotel['destination_id']  = $objToSync->destination_id ;

                $dbHotelId = $app['hotelsHandler']->sync($hotel);
                $objToSync->hotel_id = $dbHotelId;
            }
            else {
                $objToSync->hotel_id = 0;
            }

            $transport = array();
            $transport['name']       = $objToSync->transport_name;
            $transport['url']         = $objToSync->transport_url;

            $dbTransportId = $app['transportHandler']->sync($transport);
            $objToSync->transport_id = $dbTransportId;

            $objToSync->active = 1;
            $app['offersHandler']->sync($providerInfo, $objToSync);

            //if(intval($objToSync->id) == 78) {
            //    prd($objToSync);
            //}

            //images
            //delete the existing ones
            $app['imagesHandler']->delete(array('provider_id' => $providerInfo['id'], 'offer_id' => $objToSync->id));

            if(!empty($objToSync->images) AND is_array($objToSync->images)) {
                foreach($objToSync->images as $i) {

                    if(!empty($i->src)) {
                        $i->offer_id    = $objToSync->id;
                        $i->provider_id = $providerInfo['id'];
                        $app['imagesHandler']->save((array) $i);
                    }
                }
            }
            //prd($objToSync);
            unset($allIds[$objToSync->id]);

        }

        $app['offersHandler']->markInactive($providerInfo['id'], $allIds);

        //update the number of hotels, circuits, packages for each country / destination / hotel
    }

    /**
     * this function updates the number of hotels,
     * circuits, packages for each country and destination
     * */
    static function updateCounters($app) {

        $app['countriesHandler']->updateCounters($app);
        $app['destinationsHandler']->updateCounters($app);

        return;

    }

}
