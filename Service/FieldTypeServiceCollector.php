<?php


namespace DigipolisGent\SettingBundle\Service;

use DigipolisGent\SettingBundle\Exception\FieldTypeNotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Class FieldTypeServiceCollector
 * @package DigipolisGent\SettingBundle\Service\FieldTypeServiceCollector
 */
class FieldTypeServiceCollector
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    private static $fieldTypeList = array();

    /**
     * @param $name
     * @param $class
     */
    public function addFieldTypeService($name, $class)
    {
        static::$fieldTypeList[$name] = $class;
    }

    /**
     * @param $name
     * @return string
     * @throws FieldTypeNotFoundException
     */
    public function getFieldTypeService($name)
    {
        if (!isset(static::$fieldTypeList[$name])) {
            throw new FieldTypeNotFoundException();
        }

        return $this->container->get(static::$fieldTypeList[$name]);
    }
}