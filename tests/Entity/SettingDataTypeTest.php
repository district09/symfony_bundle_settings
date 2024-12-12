<?php


namespace DigipolisGent\SettingBundle\Tests\Entity;


use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class SettingDataTypeTest extends TestCase
{

    public function testSettingEntityType(){
        $dataType = new SettingDataType();
        $this->assertCount(0,$dataType->getSettingEntityTypes());

        $settingEntityTypeOne = new SettingEntityType();
        $dataType->addSettingEntityType($settingEntityTypeOne);
        $this->assertCount(1, $dataType->getSettingEntityTypes());

        $settingEntityTypeTwo = new SettingEntityType();
        $dataType->addSettingEntityType($settingEntityTypeTwo);
        $this->assertCount(2, $dataType->getSettingEntityTypes());

        $dataType->removeSettingEntityType($settingEntityTypeOne);
        $this->assertCount(1, $dataType->getSettingEntityTypes());

        $collection = new ArrayCollection();
        $collection->add($settingEntityTypeOne);
        $collection->add($settingEntityTypeTwo);

        $dataType->setSettingEntityTypes($collection);
        $this->assertCount(2, $dataType->getSettingEntityTypes());
    }

    public function testSettingDataValue(){
        $dataType = new SettingDataType();
        $this->assertCount(0,$dataType->getSettingDataValues());

        $settingDataValueOne = new SettingDataValue();
        $dataType->addSettingDataValue($settingDataValueOne);
        $this->assertCount(1, $dataType->getSettingDataValues());

        $settingDataValueTwo = new SettingDataValue();
        $dataType->addSettingDataValue($settingDataValueTwo);
        $this->assertCount(2, $dataType->getSettingDataValues());

        $collection = new ArrayCollection();
        $collection->add($settingDataValueOne);
        $collection->add($settingDataValueTwo);

        $dataType->setSettingDataValues($collection);
        $this->assertCount(2, $dataType->getSettingDataValues());
    }

}