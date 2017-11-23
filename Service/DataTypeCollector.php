<?php


namespace DigipolisGent\SettingBundle\Service;

use DigipolisGent\SettingBundle\Provider\DataTypeProviderInterface;

/**
 * Class DataTypeCollector
 * @package DigipolisGent\SettingBundle\Service
 */
class DataTypeCollector
{

    private static $dataTypeList = array();

    /**
     * @param $name
     * @param $class
     */
    public function addDataTypes(DataTypeProviderInterface $dataTypeProvider)
    {
        static::$dataTypeList = array_merge(static::$dataTypeList, $dataTypeProvider->getDataTypes());
    }

    /**
     * @return array
     */
    public function getDataTypes()
    {
        return static::$dataTypeList;
    }
}