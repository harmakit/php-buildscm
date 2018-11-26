<?php
/**
 * Created by PhpStorm.
 * User: harmakit
 * Date: 18/11/2018
 * Time: 11:50
 */

class Characteristic
{
    private $parametersCount;

    private $characteristicsValues = [];

    public function __construct($parametersCount, $characteristicsValues)
    {
        if (($parametersCount) !== count($characteristicsValues)) {
            throw new Exception('Wrong parameters count in characteristic creation');
        }

        $this->parametersCount = $parametersCount;
        $this->characteristicsValues = $characteristicsValues;
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
    public function getCharacteristicsValues()
    {
        return $this->characteristicsValues;
    }

    public function getCharacteristicsOverlay(array $observations)
    {
        $covers = [];
        $errors = [];

        foreach ($observations as $observation) {
            $success = true;

            $values = $observation->getObservationValues();
            foreach ($this->characteristicsValues as $key => $value) {
                if ($value === null) {
                    continue;
                }
                if ($value !== $values[$key]) {
                    $success = false;
                    break;
                }
            }

            if ($success) {
                $covers[] = $observation;
            } else {
                $errors[] = $observation;
            }

        }

        return [$covers, $errors];
    }

}