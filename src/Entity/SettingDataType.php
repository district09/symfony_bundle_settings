<?php


namespace DigipolisGent\SettingBundle\Entity;

use DigipolisGent\SettingBundle\Entity\Traits\IdentifiableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class SettingDataType
 * @package DigipolisGent\SettingBundle\Entity
 */
#[ORM\Table(name: 'setting_data_type')]
#[ORM\Entity]
#[UniqueEntity(fields: ['key'])]
class SettingDataType
{

    use IdentifiableTrait;

    /**
     * @var string
     */
    #[ORM\Column(name: 'setting_dt_key', type: 'string')]
    private $key;

    /**
     * @var string
     */
    #[ORM\Column(name: 'setting_dt_label', type: 'string')]
    private $label;

    /**
     * @var bool
     */
    #[ORM\Column(name: 'setting_dt_required', type: 'boolean')]
    private $required;

    /**
     * @var string
     */
    #[ORM\Column(name: 'setting_dt_field_type', type: 'string')]
    private $fieldType;

    /**
     * @var ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: SettingDataValue::class, mappedBy: 'settingDataType', cascade: ['remove'])]
    private $settingDataValues;

    /**
     * @var ArrayCollection
     */
    #[ORM\JoinTable(name: 'setting_data_type_setting_entity_type')]
    #[ORM\ManyToMany(targetEntity: SettingEntityType::class, inversedBy: 'settingDataTypes', cascade: ['persist'])]
    private $settingEntityTypes;

    /**
     * @var integer
     */
    #[ORM\Column(name: 'setting_dt_order', type: 'integer', nullable: true)]
    private $order;

    /**
     * @var string
     */
    #[ORM\Column(name: 'default_value', type: 'string', nullable: true)]
    private $defaultValue;

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

        return $this;
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

        return $this;
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

        return $this;
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

        return $this;
    }

    /**
     * @param SettingDataValue $settingDataValue
     */
    public function addSettingDataValue(SettingDataValue $settingDataValue)
    {
        $this->settingDataValues->add($settingDataValue);

        return $this;
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

        return $this;
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

        return $this;
    }

    public function clearSettingEntityTypes()
    {
        $this->settingEntityTypes->clear();
    }

    /**
     * @return string
     */
    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    /**
     * @param string $defaultValue
     */
    public function setDefaultValue(string $defaultValue = null)
    {
        $this->defaultValue = $defaultValue;
    }
}
