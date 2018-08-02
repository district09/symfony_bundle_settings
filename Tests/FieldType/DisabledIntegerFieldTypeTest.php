<?php


namespace DigipolisGent\SettingBundle\Tests\FieldType;


use DigipolisGent\SettingBundle\FieldType\DisabledIntegerFieldType;
use PHPUnit\Framework\TestCase;

class DisabledIntegerFieldTypeTest extends TestCase
{

    public function testGetName()
    {
        $this->assertEquals('disabled_integer', DisabledIntegerFieldType::getName());
    }

    public function testGetOptions()
    {
        $integerFieldType = new DisabledIntegerFieldType();
        $expected = [
            'attr' => [
                'min' => 0,
                'value' => 100,
                'readonly' => true
            ]
        ];

        $this->assertEquals($expected, $integerFieldType->getOptions(100));
    }
}
