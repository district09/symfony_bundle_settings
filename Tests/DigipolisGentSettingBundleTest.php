<?php


namespace DigipolisGent\SettingBundle\Tests;


use DigipolisGent\SettingBundle\DependencyInjection\Compiler\DataTypeCompilerPass;
use DigipolisGent\SettingBundle\DependencyInjection\Compiler\EntityTypeCompilerPass;
use DigipolisGent\SettingBundle\DependencyInjection\Compiler\FieldTypeServiceCompilerPass;
use DigipolisGent\SettingBundle\DigipolisGentSettingBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DigipolisGentSettingBundleTest extends TestCase
{
    public function testBuild()
    {
        $bundle = new DigipolisGentSettingBundle();

        $container = $this->getMockBuilder(ContainerBuilder::class)->disableOriginalConstructor()->getMock();

        $container->expects($this->at(0))->method('addCompilerPass')
            ->with($this->isInstanceOf(FieldTypeServiceCompilerPass::class));

        $container->expects($this->at(1))->method('addCompilerPass')
            ->with($this->isInstanceOf(EntityTypeCompilerPass::class));

        $container->expects($this->at(2))->method('addCompilerPass')
            ->with($this->isInstanceOf(DataTypeCompilerPass::class));

        $bundle->build($container);
    }
}