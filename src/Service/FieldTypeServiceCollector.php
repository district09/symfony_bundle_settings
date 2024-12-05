<?php


namespace DigipolisGent\SettingBundle\Service;

use DigipolisGent\SettingBundle\Exception\FieldTypeNotFoundException;
use DigipolisGent\SettingBundle\FieldType\FieldTypeInterface;

/**
 * Class FieldTypeServiceCollector
 * @package DigipolisGent\SettingBundle\Service\FieldTypeServiceCollector
 */
class FieldTypeServiceCollector
{

    protected static $fieldTypeList;

    /**
     * @param FieldTypeInterface[] $fieldTypeList
     */
    public function collectFieldTypes(iterable $fieldTypeList)
    {
        foreach ($fieldTypeList as $fieldType) {
            $this->addFieldTypeService($fieldType::getName(), $fieldType);
        }
    }

    /**
     * @param $name
     * @param $class
     */
    public function addFieldTypeService($name, FieldTypeInterface $fieldType)
    {
        static::$fieldTypeList[$name] = $fieldType;
    }

    /**
     * @param $name
     * @return string
     * @throws FieldTypeNotFoundException
     */
    public function getFieldTypeService($name)
    {
        if (!isset(static::$fieldTypeList[$name])) {
            throw new FieldTypeNotFoundException('No fieldtype found for ' . $name);
        }

        return static::$fieldTypeList[$name];
    }
}
