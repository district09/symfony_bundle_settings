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
        $container = $this->getContainerMock();
        $container
            ->expects($this->at(0))
            ->method('get')
            ->with(StringFieldType::class)
            ->willReturn(new StringFieldType());

        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollector($container);
        $fieldTypeService = $fieldTypeServiceCollector->getFieldTypeService('string');
        $this->assertInstanceOf(StringFieldType::class, $fieldTypeService);
        $this->assertEquals('string', $fieldTypeService::getName());
    }

    /**
     * @expectedException \DigipolisGent\SettingBundle\Exception\FieldTypeNotFoundException
     */
    public function testGetFieldTypeServiceException()
    {
        $container = $this->getContainerMock();
        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollector($container);
        $fieldTypeServiceCollector->getFieldTypeService('random');
    }

    private function getFieldTypeServiceCollector($container)
    {
        $fieldTypeServiceCollector = new FieldTypeServiceCollector($container);
        $fieldTypeServiceCollector->addFieldTypeService('string', StringFieldType::class);
        $fieldTypeServiceCollector->addFieldTypeService('boolean', BooleanFieldType::class);

        return $fieldTypeServiceCollector;
    }

    private function getContainerMock()
    {
        $mock = $this
            ->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

}