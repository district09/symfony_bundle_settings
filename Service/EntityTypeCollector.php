<?php


namespace DigipolisGent\SettingBundle\Service;

use DigipolisGent\SettingBundle\Provider\EntityTypeProviderInterface;

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
    public function getEntityTypeByClass($class)
    {
        $flippedArr = array_flip(self::$entityTypeList);

        $name = null;

        if (isset($flippedArr[$class])) {
            $name = $flippedArr[$class];
        }

        $parentClass = get_parent_class($class);

        if ($parentClass && isset($flippedArr[$parentClass])) {
            $name = $flippedArr[$parentClass];
        }

        return $name;
    }
}