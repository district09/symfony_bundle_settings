<?php


namespace DigipolisGent\SettingBundle\Tests\FieldType;


use DigipolisGent\SettingBundle\FieldType\BooleanFieldType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class BooleanFieldTypeTest extends TestCase
{

    public function testGetName()
    {
        $this->assertEquals('boolean', BooleanFieldType::getName());
    }

    public function testGetFormType()
    {
        $booleanFieldType = new BooleanFieldType();
        $this->assertEquals(CheckboxType::class, $booleanFieldType->getFormType());
    }

    public function testGetOptions()
    {
        $booleanFieldType = new BooleanFieldType();

        $options = $booleanFieldType->getOptions(true);
        $expected = [
            'attr' => [
                'checked' => 'checked'
            ]
        ];
        $this->assertEquals($expected, $options);

        $options = $booleanFieldType->getOptions(false);
        $expected = [];
        $this->assertEquals($expected, $options);
    }

    public function testDecodeValue()
    {
        $booleanFieldType = new BooleanFieldType();
        $this->assertTrue($booleanFieldType->decodeValue(1));
        $this->assertTrue($booleanFieldType->decodeValue('1'));
        $this->assertTrue($booleanFieldType->decodeValue('true'));
        $this->assertTrue($booleanFieldType->decodeValue(true));
        $this->assertFalse($booleanFieldType->decodeValue(0));
        $this->assertFalse($booleanFieldType->decodeValue('0'));
        $this->assertFalse($booleanFieldType->decodeValue(false));
        $this->assertFalse($booleanFieldType->decodeValue(''));
    }

    public function testEncodeValue()
    {
        $booleanFieldType = new BooleanFieldType();

        $this->assertEquals('1', $booleanFieldType->encodeValue(true));
    }


}