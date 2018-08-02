<?php


namespace DigipolisGent\SettingBundle\Tests\DependencyInjection\Compiler;


use DigipolisGent\SettingBundle\DependencyInjection\Compiler\FieldTypeServiceCompilerPass;
use DigipolisGent\SettingBundle\FieldType\BooleanFieldType;
use DigipolisGent\SettingBundle\FieldType\StringFieldType;
use DigipolisGent\SettingBundle\Service\FieldTypeServiceCollector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class FieldTypeServiceCompilerPassTest extends TestCase
{


    public function testTaggedServices()
    {
        $container = $this->getContainerBuilderMock();

        $taggedServices = [
            StringFieldType::class => [],
            BooleanFieldType::class => [],
        ];

        $container
            ->expects($this->at(0))
            ->method('findTaggedServiceIds')
            ->with('field_type')
            ->willReturn($taggedServices);

        $definition = new Definition(FieldTypeServiceCollector::class);

        $container
            ->expects($this->at(1))
            ->method('findDefinition')
            ->with(FieldTypeServiceCollector::class)
            ->willReturn(
                $definition
            );

        $compilerPass = new FieldTypeServiceCompilerPass();
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