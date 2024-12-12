<?php


namespace DigipolisGent\SettingBundle\Tests\Entity;


use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class SettingEntityTypeTest extends TestCase
{

    public function testSettingDataTypes()
    {
        $entityType = new SettingEntityType();
        $this->assertCount(0, $entityType->getSettingDataTypes());

        $settingDataTypeOne = new SettingDataType();
        $entityType->addSettingDataType($settingDataTypeOne);
        $this->assertCount(1, $entityType->getSettingDataTypes());

        $settingDataTypeTwo = new SettingDataType();
        $entityType->addSettingDataType($settingDataTypeTwo);
        $this->assertCount(2, $entityType->getSettingDataTypes());

        $entityType->removeSettingDataType($settingDataTypeOne);
        $this->assertCount(1, $entityType->getSettingDataTypes());

        $collection = new ArrayCollection();
        $collection->add($settingDataTypeOne);
        $collection->add($settingDataTypeTwo);

        $entityType->setSettingDataTypes($collection);
        $this->assertCount(2, $entityType->getSettingDataTypes());
    }

}