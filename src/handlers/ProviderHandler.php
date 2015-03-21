<?php

namespace Travel\Handlers;

class ProviderHandler extends GenericHandler {

    private $dbHandler;

    function __construct($dbHandler) {

        $this->dbHandler = $dbHandler;

    }

    function getDbHandler() {
        return $this->dbHandler;
    }

    function findOneByIdent($ident) {
        return $this->getDbHandler()->findOneByIdent($ident);
    }

    function getForList(\Symfony\Component\HttpFoundation\Request $request, $urlGenerator = null ) {
        $dbData = $this->getDbHandler()->getForList($request);

        foreach($dbData['results'] as $index => $arr) {
            $dbData['results'][$index][2] = '<a class="btn btn-primary btn-xs" href="'.$urlGenerator->generate('sync', array('provider_ident' => $arr[1])).'">Sincronizeaza</a>';
        }

        return $dbData;
    }
}
