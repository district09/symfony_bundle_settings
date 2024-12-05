<?php


namespace DigipolisGent\SettingBundle\Tests\FieldType;


use DigipolisGent\SettingBundle\FieldType\StringFieldType;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Foo;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class StringFieldTypeTest extends TestCase
{

    public function testGetName()
    {
        $this->assertEquals('string', StringFieldType::getName());
    }

    public function testGetFormType()
    {
        $stringFieldType = new StringFieldType();
        $this->assertEquals(TextType::class, $stringFieldType->getFormType());
    }

    public function testGetOptions()
    {
        $stringFieldType = new StringFieldType();
        $expected = [
            'attr' => [
                'value' => 'random'
            ]
        ];
        $this->assertEquals($expected, $stringFieldType->getOptions('random'));
    }

    public function testSetOriginEntity()
    {
        $stringFieldType = new StringFieldType();
        $stringFieldType->setOriginEntity(new Foo());
        $this->assertInstanceOf(Foo::class, $stringFieldType->getOriginEntity());
    }
}