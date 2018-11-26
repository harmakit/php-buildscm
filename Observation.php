<?php
/**
 * Created by PhpStorm.
 * User: harmakit
 * Date: 18/11/2018
 * Time: 11:50
 */

class Observation
{
    private $parametersCount;

    private $type;

    private $observationValues = [];

    public function __construct($parametersCount, $observationValues, $type)
    {
        if (($parametersCount) !== count($observationValues)) {
            throw new Exception('Wrong parameters count in characteristic creation');
        }

        $this->type = $type;
        $this->parametersCount = $parametersCount;
        $this->observationValues = $observationValues;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getParametersCount()
    {
        return $this->parametersCount;
    }

    /**
     * @return array
     */
    public function getObservationValues()
    {
        return $this->observationValues;
    }
        
}