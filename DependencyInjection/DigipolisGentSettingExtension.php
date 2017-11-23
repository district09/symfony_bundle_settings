<?php

namespace DigipolisGent\SettingBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class DigipolisGentSettingExtension
 * @package DigipolisGent\SettingBundle\DependencyInjection
 */
class DigipolisGentSettingExtension extends Extension
{

    /**
     * @param array $configs
     * @param ContainerBuilder $containerBuilder
     */
    public function load(array $configs, ContainerBuilder $containerBuilder)
    {

        $loader = new YamlFileLoader(
            $containerBuilder,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');

    }

}
