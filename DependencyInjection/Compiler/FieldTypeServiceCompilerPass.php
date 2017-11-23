<?php


namespace DigipolisGent\SettingBundle\DependencyInjection\Compiler;


use DigipolisGent\SettingBundle\Service\FieldTypeServiceCollector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class FieldTypeServiceCompilerPass
 * @package DigipolisGent\SettingBundle\DependencyInjection\Compiler
 */
class FieldTypeServiceCompilerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds('field_type');
        $definition = $container->findDefinition(FieldTypeServiceCollector::class);
        $taggedServiceClasses = array_keys($taggedServices);

        foreach ($taggedServiceClasses as $class) {
            $definition->addMethodCall('addFieldTypeService', [$class::getName(), $class]);
        }
    }

}