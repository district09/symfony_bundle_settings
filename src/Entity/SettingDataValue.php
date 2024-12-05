<?php


namespace DigipolisGent\SettingBundle\Entity;

use DigipolisGent\SettingBundle\Entity\Traits\IdentifiableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class SettingDataValue
 * @package DigipolisGent\SettingBundle\Entity
 */
#[ORM\Table(name: 'setting_data_value')]
#[ORM\Entity(repositoryClass: \DigipolisGent\SettingBundle\Entity\Repository\SettingDataValueRepository::class)]
class SettingDataValue
{

    use IdentifiableTrait;

    /**
     * @var string
     */
    #[ORM\Column(name: 'setting_v_value', type: 'text', nullable: true, options: ['default' => null])]
    private $value;

    /**
     * @var SettingDataType
     */
    #[ORM\JoinColumn(referencedColumnName: 'id', name: 'setting_data_type_id')]
    #[ORM\ManyToOne(targetEntity: SettingDataType::class, inversedBy: 'settingDataValues')]
    private $settingDataType;

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return SettingDataType
     */
    public function getSettingDataType()
    {
        return $this->settingDataType;
    }

    /**
     * @param SettingDataType $settingDataType
     */
    public function setSettingDataType($settingDataType)
    {
        $this->settingDataType = $settingDataType;
    }
}
