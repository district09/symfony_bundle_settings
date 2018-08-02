<?php

namespace DigipolisGent\SettingBundle;

use DigipolisGent\SettingBundle\DependencyInjection\Compiler\DataTypeCompilerPass;
use DigipolisGent\SettingBundle\DependencyInjection\Compiler\FieldTypeServiceCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class DigipolisGentSettingBundle
 * @package DigipolisGent\SettingBundle
 */
class DigipolisGentSettingBundle extends Bundle
{

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FieldTypeServiceCompilerPass());
        $container->addCompilerPass(new DataTypeCompilerPass());
    }
}
