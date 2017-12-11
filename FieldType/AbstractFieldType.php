<?php


namespace DigipolisGent\SettingBundle\FieldType;


use Doctrine\ORM\Mapping\Entity;

/**
 * Class AbstractFieldType
 * @package DigipolisGent\SettingBundle\FieldType
 */
abstract class AbstractFieldType implements FieldTypeInterface
{

    protected $originEntity;

    /**
     * @param Entity $entity
     */
    public function setOriginEntity($entity)
    {
        $this->originEntity = $entity;
    }

    /**
     * @return mixed
     */
    public function getOriginEntity(){
        return $this->originEntity;
    }

}