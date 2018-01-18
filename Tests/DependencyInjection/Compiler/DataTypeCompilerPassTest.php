<?php


namespace DigipolisGent\SettingBundle\Tests\DependencyInjection\Compiler;


use DigipolisGent\SettingBundle\DependencyInjection\Compiler\DataTypeCompilerPass;
use DigipolisGent\SettingBundle\Service\DataTypeCollector;
use DigipolisGent\SettingBundle\Tests\Fixtures\Provider\AbstractDataTypeProvider;
use DigipolisGent\SettingBundle\Tests\Fixtures\Provider\DataTypeProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class DataTypeCompilerPassTest extends TestCase
{

    public function testTaggedServices()
    {
        $container = $this->getContainerBuilderMock();

        $container
            ->expects($this->at(0))
            ->method('getDefinition')
            ->with($this->equalTo(DataTypeCollector::class))
            ->willReturn(
                new Definition(DataTypeCollector::class)
            );

        $taggedServices = [
            DataTypeProvider::class => []
        ];

        $container
            ->expects($this->at(1))
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('data_type_provider'))
            ->willReturn($taggedServices);

        $dataTypes = [
            [
                'key' => 'foo_bar',
                'label' => 'My label',
                'required' => true,
                'field_type' => 'my_field_type',
                'entity_types' => ['foo'],
            ]
        ];

        $container
            ->expects($this->at(2))
            ->method('getDefinition')
            ->with($this->equalTo(DataTypeProvider::class))
            ->willReturn(new Definition(DataTypeProvider::class, $dataTypes));


        $compilerPass = new DataTypeCompilerPass();
        $compilerPass->process($container);
    }

    public function testTaggedServicesWithAbstractProvider()
    {
        $container = $this->getContainerBuilderMock();

        $container
            ->expects($this->at(0))
            ->method('getDefinition')
            ->with($this->equalTo(DataTypeCollector::class))
            ->willReturn(
                new Definition(DataTypeCollector::class)
            );

        $taggedServices = [
            AbstractDataTypeProvider::class => []
        ];

        $container
            ->expects($this->at(1))
            ->method('findTaggedServiceIds')
            ->with($this->equalTo('data_type_provider'))
            ->willReturn($taggedServices);

        $container
            ->expects($this->at(2))
            ->method('getDefinition')
            ->with($this->equalTo(AbstractDataTypeProvider::class))
            ->willReturn((new Definition(AbstractDataTypeProvider::class))->setAbstract(true));

        $compilerPass = new DataTypeCompilerPass();
        $compilerPass->process($container);
    }

    private function getContainerBuilderMock()
    {
        $mock = $this
            ->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

}