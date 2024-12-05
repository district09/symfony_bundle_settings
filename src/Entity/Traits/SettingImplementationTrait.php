<?php


namespace DigipolisGent\SettingBundle\Entity\Traits;

use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Trait SettingImplementationTrait
 * @package DigipolisGent\SettingBundle\Entity\Traits
 */
trait SettingImplementationTrait
{

    /**
     * @var ArrayCollection
     */
    protected $settingDataValues;

    /**
     * @param SettingDataValue $settingDataValue
     */
    public function addSettingDataValue(SettingDataValue $settingDataValue)
    {
        $this->getSettingDataValues()->add($settingDataValue);
    }

    /**
     * @return ArrayCollection
     */
    public function getSettingDataValues()
    {
        if (is_null($this->settingDataValues)) {
            $this->settingDataValues = new ArrayCollection();
        }
        return $this->settingDataValues;
    }

    public function clearSettingDataValues()
    {
        $this->getSettingDataValues()->clear();
    }

    abstract static function getSettingImplementationName();

    public function getConfig(string $key): ?string
    {
        foreach ($this->getSettingDataValues() as $settingDataValue) {
            if ($settingDataValue->getSettingDataType()->getKey() == $key) {
                return $settingDataValue->getValue();
            }
        }

        return '';
    }
}
