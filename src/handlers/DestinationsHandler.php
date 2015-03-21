<?php
namespace Travel\Handlers;

class DestinationsHandler extends GenericHandler {

    private $dbHandler;

    function __construct($dbHandler) {

        $this->dbHandler = $dbHandler;

    }

    function getDbHandler() {
        return $this->dbHandler;
    }

    function getBy($fields) {
        return $this->getDbHandler()->getBy($fields);
    }

    function sync($data, $full = false) {

        if($full) {
            $auxData = array(
                'title'         => $data->title
                ,'url'          => $data->url
                ,'info_text'    => $data->info_text
                ,'description'  => $data->description
                ,'informations' => $data->informations
                ,'lat'          => $data->latitude
                ,'lon'          => $data->longitude
                ,'zoom'         => $data->zoom
                ,'country_id'   => $data->local_country_id
                ,'visible'      => $data->visible
            );

            $data = $auxData;
        }

        return $this->getDbHandler()->sync($data, $full);

    }

    function updateCounters() {
        return $this->getDbHandler()->updateCounters();
    }
}
