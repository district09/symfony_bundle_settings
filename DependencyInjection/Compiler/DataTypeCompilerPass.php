<?php


namespace DigipolisGent\SettingBundle\DependencyInjection\Compiler;

use DigipolisGent\SettingBundle\Service\DataTypeCollector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DataTypeCompilerPass
 * @package DigipolisGent\SettingBundle\DependencyInjection\Compiler
 */
class DataTypeCompilerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition(DataTypeCollector::class);

        $taggedServices = $container->findTaggedServiceIds('data_type_provider');

        foreach ($taggedServices as $id => $tags) {
            $serviceDefinition = $container->getDefinition($id);

            if ($serviceDefinition->isAbstract()) {
                continue;
            }

            $definition->addMethodCall('addDataTypes', array(new Reference($id)));
        }
    }
}
