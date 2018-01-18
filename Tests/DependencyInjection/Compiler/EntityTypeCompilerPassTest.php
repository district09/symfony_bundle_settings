<?php


namespace DigipolisGent\SettingBundle\Tests\DependencyInjection\Compiler;


use DigipolisGent\SettingBundle\DependencyInjection\Compiler\EntityTypeCompilerPass;
use DigipolisGent\SettingBundle\Service\EntityTypeCollector;
use DigipolisGent\SettingBundle\Tests\Fixtures\Provider\AbstractEntityTypeProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class EntityTypeCompilerPassTest extends TestCase
{

    public function testTaggedServices()
    {
        $container = $this->getContainerBuilderMock();

        $container
            ->expects($this->at(0))
            ->method('getDefinition')
            ->with(EntityTypeCollector::class)
            ->willReturn(
                new Definition(EntityTypeCollector::class)
            );

        $taggedServices = [
            EntityTypeProvider::class => [],
        ];

        $container
            ->expects($this->at(1))
            ->method('findTaggedServiceIds')
            ->with('entity_type_provider')
            ->willReturn($taggedServices);

        $container
            ->expects($this->at(2))
            ->method('getDefinition')
            ->with(EntityTypeProvider::class)
            ->willReturn(
                new Definition(
                    EntityTypeProvider::class,
                    ['foo' => Foo::class, 'bar' => Bar::class]
                )
            );

        $compilerPass = new EntityTypeCompilerPass();
        $compilerPass->process($container);
    }

    public function testTaggedServicesWithAbstractClass()
    {
        $container = $this->getContainerBuilderMock();

        $container
            ->expects($this->at(0))
            ->method('getDefinition')
            ->with(EntityTypeCollector::class)
            ->willReturn(
                new Definition(EntityTypeCollector::class)
            );

        $taggedServices = [
            AbstractEntityTypeProvider::class => [],
        ];

        $container
            ->expects($this->at(1))
            ->method('findTaggedServiceIds')
            ->with('entity_type_provider')
            ->willReturn($taggedServices);

        $container
            ->expects($this->at(2))
            ->method('getDefinition')
            ->with(AbstractEntityTypeProvider::class)
            ->willReturn(
                (new Definition(AbstractEntityTypeProvider::class))->setAbstract(true)
            );

        $compilerPass = new EntityTypeCompilerPass();
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