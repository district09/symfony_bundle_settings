<?php


namespace DigipolisGent\SettingBundle\Tests\Service;


use DigipolisGent\SettingBundle\FieldType\BooleanFieldType;
use DigipolisGent\SettingBundle\FieldType\StringFieldType;
use DigipolisGent\SettingBundle\Service\FieldTypeServiceCollector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FieldTypeServiceCollectorTest extends TestCase
{

    public function testGetFieldTypeService()
    {

        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollector();
        $fieldTypeService = $fieldTypeServiceCollector->getFieldTypeService('string');
        $this->assertInstanceOf(StringFieldType::class, $fieldTypeService);
        $this->assertEquals('string', $fieldTypeService::getName());
    }

    /**
     * @expectedException \DigipolisGent\SettingBundle\Exception\FieldTypeNotFoundException
     */
    public function testGetFieldTypeServiceException()
    {
        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollector();
        $fieldTypeServiceCollector->getFieldTypeService('random');
    }

    private function getFieldTypeServiceCollector()
    {
        $fieldTypeServiceCollector = new FieldTypeServiceCollector();
        $fieldTypeServiceCollector->addFieldTypeService('string', new StringFieldType());
        $fieldTypeServiceCollector->addFieldTypeService('boolean', new BooleanFieldType());

        return $fieldTypeServiceCollector;
    }

}
