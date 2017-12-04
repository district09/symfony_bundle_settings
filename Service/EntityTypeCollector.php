<?php


namespace DigipolisGent\SettingBundle\Service;

use DigipolisGent\SettingBundle\Exception\FieldTypeNotFoundException;
use DigipolisGent\SettingBundle\Provider\EntityTypeProviderInterface;
use Psr\Container\ContainerInterface;

/**
 * Class EntityTypeCollector
 * @package DigipolisGent\SettingBundle\Service
 */
class EntityTypeCollector
{

    private static $entityTypeList = array();

    /**
     * @param $name
     * @param $class
     */
    public function addEntityTypes(EntityTypeProviderInterface $entityTypeProvider)
    {
        foreach ($entityTypeProvider->getEntityTypes() as $name => $class) {
            static::$entityTypeList[$name] = $class;
        }
    }

    /**
     * @return array
     */
    public function getEntityTypes()
    {
        return static::$entityTypeList;
    }

    /**
     * @param $class
     * @return string
     */
    public function getEntityTypeByClass($class){
        $flippedArr = array_flip(self::$entityTypeList);
        return $flippedArr[$class];
    }
}