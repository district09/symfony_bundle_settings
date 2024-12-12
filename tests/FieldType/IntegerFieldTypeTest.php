<?php


namespace DigipolisGent\SettingBundle\Tests\FieldType;


use DigipolisGent\SettingBundle\FieldType\IntegerFieldType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class IntegerFieldTypeTest extends TestCase
{

    public function testGetName()
    {
        $this->assertEquals('integer', IntegerFieldType::getName());
    }

    public function testGetFormType()
    {
        $integerFieldType = new IntegerFieldType();
        $this->assertEquals(IntegerType::class, $integerFieldType->getFormType());
    }

    public function testGetOptions()
    {
        $integerFieldType = new IntegerFieldType();
        $expected = [
            'attr' => [
                'min' => 0,
                'value' => 100
            ]
        ];

        $this->assertEquals($expected, $integerFieldType->getOptions('100'));
        $this->assertEquals($expected, $integerFieldType->getOptions(100));
    }

    public function testDecodeValue()
    {
        $integerFieldType = new IntegerFieldType();
        $this->assertEquals(100, $integerFieldType->decodeValue('100'));
        $this->assertEquals(0, $integerFieldType->decodeValue('0'));
        $this->assertEquals(0, $integerFieldType->decodeValue(''));
    }

    public function testEncodeValue()
    {
        $integerFieldType = new IntegerFieldType();
        $this->assertEquals('100', $integerFieldType->encodeValue(100));
        $this->assertEquals('0', $integerFieldType->encodeValue(0));
    }


}