<?php
/**
 * Created by PhpStorm.
 * User: harmakit
 * Date: 22/11/2018
 * Time: 18:45
 */
require_once 'Characteristic.php';

class CharacteristicFabric
{
    private $parametersCount = 0;

    public function setParametersCount($pc)
    {
        $this->parametersCount = $pc;
    }

    /**
     * @return int
     */
    public function getParametersCount()
    {
        return $this->parametersCount;
    }

    public function create(array $values)
    {
        foreach ($values as $key => $value) {
            if ($value !== 'x') {
                $values[$key] = (int) (bool) $value;
            } else {
                $values[$key] = null;
            }
        }
        return new Characteristic($this->parametersCount, $values);
    }
}