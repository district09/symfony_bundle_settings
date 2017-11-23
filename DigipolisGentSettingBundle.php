<?php

namespace DigipolisGent\SettingBundle;


use DigipolisGent\SettingBundle\DependencyInjection\Compiler\FieldTypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DigipolisGentSettingBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FieldTypeCompilerPass());
    }

}