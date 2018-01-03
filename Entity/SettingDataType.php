<?php


namespace DigipolisGent\SettingBundle\Entity;

use DigipolisGent\SettingBundle\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class SettingDataType
 * @package DigipolisGent\SettingBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="setting_data_type")
 * @UniqueEntity(fields={"key"})
 */
class SettingDataType
{

    use IdentifiableTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_dt_key",type="string")
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_dt_label",type="string")
     */
    private $label;

    /**
     * @var bool
     *
     * @ORM\Column(name="setting_dt_required",type="boolean")
     */
    private $required;

    /**
     * @var string
     *
     * @ORM\Column(name="setting_dt_field_type",type="string")
     */
    private $fieldType;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity=SettingDataValue::class,mappedBy="settingDataType",cascade={"remove"})
     */
    private $settingDataValues;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity=SettingEntityType::class,inversedBy="settingDataTypes",cascade={"persist"})
     * @ORM\JoinTable(name="setting_data_type_setting_entity_type")
     */
    private $settingEntityTypes;

    /**
     * @var integer
     *
     * @ORM\Column(name="setting_dt_order",type="integer",nullable=true)
     */
    private $order;

    public function __construct()
    {
        $this->settingDataValues = new ArrayCollection();
        $this->settingEntityTypes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * @return string
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * @param string $fieldType
     */
    public function setFieldType($fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * @param SettingDataValue $settingDataValue
     */
    public function addSettingDataValue(SettingDataValue $settingDataValue)
    {
        $this->settingDataValues->add($settingDataValue);
    }

    /**
     * @return ArrayCollection
     */
    public function getSettingDataValues()
    {
        return $this->settingDataValues;
    }

    /**
     * @param ArrayCollection $settingDataValues
     */
    public function setSettingDataValues($settingDataValues)
    {
        $this->settingDataValues = $settingDataValues;
    }

    /**
     * @return ArrayCollection
     */
    public function getSettingEntityTypes()
    {
        return $this->settingEntityTypes;
    }

    /**
     * @param ArrayCollection $settingEntityTypes
     */
    public function setSettingEntityTypes($settingEntityTypes)
    {
        $this->settingEntityTypes = $settingEntityTypes;
    }

    /**
     * @param SettingEntityType $settingEntityType
     */
    public function addSettingEntityType(SettingEntityType $settingEntityType)
    {
        $this->settingEntityTypes->add($settingEntityType);
        $settingEntityType->addSettingDataType($this);
    }

    /**
     * @param SettingEntityType $settingEntityType
     */
    public function removeSettingEntityType(SettingEntityType $settingEntityType)
    {
        $this->settingEntityTypes->removeElement($settingEntityType);
        $settingEntityType->removeSettingDataType($this);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function clearSettingEntityTypes()
    {
        $this->settingEntityTypes->clear();
    }
}