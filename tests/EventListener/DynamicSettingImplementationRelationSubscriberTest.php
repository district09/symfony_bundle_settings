<?php


namespace DigipolisGent\SettingBundle\Tests\EventListener;


use DigipolisGent\SettingBundle\EventListener\DynamicSettingImplementationRelationSubscriber;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Bar;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Foo;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\NamingStrategy;
use PHPUnit\Framework\TestCase;

class DynamicSettingImplementationRelationSubscriberTest extends TestCase
{

    public function testLoadClassMetadataWithoutSettingsImplementation()
    {
        $metadata = $this->getMetadataMock(Bar::class);

        $loadClassMetadataEventArgsMock = $this
            ->getMockBuilder(LoadClassMetadataEventArgs::class)
            ->disableOriginalConstructor()
            ->getMock();

        $loadClassMetadataEventArgsMock
            ->expects($this->any())
            ->method('getClassMetadata')
            ->willReturn($metadata);

        $subscriber = new DynamicSettingImplementationRelationSubscriber();
        $subscriber->loadClassMetadata($loadClassMetadataEventArgsMock);
    }

    public function testLoadClassMetadataWithSettingsImplementation()
    {
        $namingStrategy = $this->getNamingStrategyMock();
        $configuration = $this->getConfigurationMock($namingStrategy);
        $entityManager = $this->getEntityManagerMock($configuration);

        $metadata = $this->getMetadataMock(Foo::class);

        $loadClassMetadataEventArgsMock = $this->getLoadClassMetadataEventArgsMock($entityManager, $metadata);

        $subscriber = new DynamicSettingImplementationRelationSubscriber();
        $subscriber->loadClassMetadata($loadClassMetadataEventArgsMock);
    }

    private function getLoadClassMetadataEventArgsMock($entityManager, $metadata)
    {
        $mock = $this
            ->getMockBuilder(LoadClassMetadataEventArgs::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getClassMetadata')
            ->willReturn($metadata);

        $mock
            ->expects($this->any())
            ->method('getEntityManager')
            ->willReturn($entityManager);

        return $mock;
    }

    private function getMetadataMock($className)
    {
        $mock = $this
            ->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();


        $mock->expects($this->any())
            ->method('isRootEntity')
            ->willReturn(true);

        $mock
            ->expects($this->any())
            ->method('getReflectionClass')
            ->willReturn(new \ReflectionClass($className));

        return $mock;
    }

    private function getEntityManagerMock($configuration)
    {
        $mock = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getConfiguration')
            ->willReturn($configuration);

        return $mock;
    }

    private function getConfigurationMock($namingStrategy)
    {
        $mock = $this
            ->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getNamingStrategy')
            ->willReturn($namingStrategy);

        return $mock;
    }

    private function getNamingStrategyMock()
    {
        $mock = $this
            ->getMockBuilder(NamingStrategy::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}
