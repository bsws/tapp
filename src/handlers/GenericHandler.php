<?php

namespace Travel\Handlers;

abstract class GenericHandler {

    abstract function getDbHandler();

    function getForList(\Symfony\Component\HttpFoundation\Request $request, $urlGenerator = null) {
        $dbData = $this->getDbHandler()->getForList($request);
        return $dbData;
    }

}
