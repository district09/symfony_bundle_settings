<?php


namespace DigipolisGent\SettingBundle\DependencyInjection\Compiler;

use DigipolisGent\SettingBundle\Service\EntityTypeCollector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class EntityTypeCompilerPass
 * @package DigipolisGent\SettingBundle\DependencyInjection\Compiler
 */
class EntityTypeCompilerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition(EntityTypeCollector::class);

        $taggedServices = $container->findTaggedServiceIds('entity_type_provider');

        foreach ($taggedServices as $id => $tags) {
            $serviceDefinition = $container->getDefinition($id);

            if ($serviceDefinition->isAbstract()) {
                continue;
            }

            $definition->addMethodCall('addEntityTypes', array(new Reference($id)));
        }
    }
}
