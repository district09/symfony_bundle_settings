<?php

namespace DigipolisGent\SettingBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

/**
 * Class DigipolisGentSettingBundle
 * @package DigipolisGent\SettingBundle
 */
class DigipolisGentSettingBundle extends AbstractBundle
{
    #[\Override]
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yml');
    }
}
