<?php


namespace DigipolisGent\SettingBundle\Entity;

use DigipolisGent\SettingBundle\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DigipolisGent\SettingBundle\Entity\SettingDataType;

/**
 * Class SettingEntityType
 * @package DigipolisGent\SettingBundle\Entity
 *
 * @ORM\Entity()
 * @UniqueEntity(fields={"name"})
 * @ORM\Table(name="setting_entity_type")
 */
class SettingEntityType
{

    use IdentifiableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_et_name",type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_et_class",type="string")
     */
    private $class;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity=SettingDataType::class,mappedBy="settingEntityTypes",cascade={"persist"})
     */
    private $settingDataTypes;

    public function __construct()
    {
        $this->settingDataTypes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @param SettingDataType $settingDataType
     */
    public function addSettingDataType(SettingDataType $settingDataType)
    {
        $this->settingDataTypes->add($settingDataType);
    }

    /**
     * @param SettingDataType $settingDataType
     */
    public function removeSettingDataType(SettingDataType $settingDataType)
    {
        $this->settingDataTypes->removeElement($settingDataType);
    }

    /**
     * @return ArrayCollection
     */
    public function getSettingDataTypes()
    {
        return $this->settingDataTypes;
    }

    /**
     * @param ArrayCollection $settingDataTypes
     */
    public function setSettingDataTypes($settingDataTypes)
    {
        $this->settingDataTypes = $settingDataTypes;
    }

}