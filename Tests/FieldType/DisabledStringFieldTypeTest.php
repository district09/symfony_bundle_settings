<?php


namespace DigipolisGent\SettingBundle\Tests\FieldType;


use DigipolisGent\SettingBundle\FieldType\DisabledStringFieldType;
use PHPUnit\Framework\TestCase;

class DisabledStringFieldTypeTest extends TestCase
{

    public function testGetName()
    {
        $this->assertEquals('disabled_string', DisabledStringFieldType::getName());
    }

    public function testGetOptions()
    {
        $disabledStringFieldType = new DisabledStringFieldType();
        $expected = [
            'attr' => [
                'value' => 'random',
                'disabled' => true
            ]
        ];
        $this->assertEquals($expected, $disabledStringFieldType->getOptions('random'));
    }

}