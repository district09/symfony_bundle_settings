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
        static::$entityTypeList = array_merge(static::$entityTypeList, $entityTypeProvider->getEntityTypes());
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

        if(!isset($flippedArr[$class])){
            return null;
        }

        return $flippedArr[$class];
    }
}