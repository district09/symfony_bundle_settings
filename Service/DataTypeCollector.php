<?php


namespace DigipolisGent\SettingBundle\Service;

use DigipolisGent\SettingBundle\Exception\KeyNotFoundException;
use DigipolisGent\SettingBundle\Provider\DataTypeProviderInterface;

/**
 * Class DataTypeCollector
 * @package DigipolisGent\SettingBundle\Service
 */
class DataTypeCollector
{

    private static $dataTypeList = array();

    private static $keys = ['key', 'label', 'required', 'field_type', 'entity_types'];

    /**
     * @param $name
     * @param $class
     */
    public function addDataTypes(DataTypeProviderInterface $dataTypeProvider)
    {
        foreach ($dataTypeProvider->getDataTypes() as $dataType) {
            $this->checkDataTypeArray($dataType);
        }

        static::$dataTypeList = array_merge(static::$dataTypeList, $dataTypeProvider->getDataTypes());
    }

    /**
     * @param array $dataType
     * @throws KeyNotFoundException
     */
    private function checkDataTypeArray(array $dataType){
        foreach (self::$keys as $key) {
            if (!array_key_exists($key, $dataType)) {
                throw new KeyNotFoundException();
            }
        }
    }

    /**
     * @return array
     */
    public function getDataTypes()
    {
        return static::$dataTypeList;
    }
}
