<?php


namespace DigipolisGent\SettingBundle\Tests\DependencyInjection;


use DigipolisGent\SettingBundle\DependencyInjection\DigipolisGentSettingExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DigipolisGentSettingExtensionTest extends TestCase
{

    public function testLoad()
    {
        $configs = [];
        $containerBuilder = $this->getContainerBuilderMock();

        $extension = new DigipolisGentSettingExtension();
        $extension->load($configs, $containerBuilder);
    }

    private function getContainerBuilderMock()
    {
        $mock = $this
            ->getMockBuilder(ContainerBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('fileExists');

        return $mock;
    }

}