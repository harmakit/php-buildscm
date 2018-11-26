<?php
/**
 * Created by PhpStorm.
 * User: harmakit
 * Date: 22/11/2018
 * Time: 18:45
 */
require_once 'Observation.php';

class ObservationFabric
{
    private $parametersCount = 0;
    private $type;

    public function setType($type)
    {
        if (!in_array($type, ['P', 'N'])) {
            throw new Exception('Type must be \'P\' or \'N\'');
        }
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getParametersCount()
    {
        return $this->parametersCount;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    public function setParametersCount($pc)
    {
        $this->parametersCount = $pc;
    }

    public function create(array $values)
    {
        foreach ($values as $key => $value) {
            $values[$key] = (int) (bool) $value;
        }
        if (!isset($this->type)) {
            throw new Exception('You didn\'t set any type');
        }
        return new Observation($this->parametersCount, $values, $this->type);
    }
}