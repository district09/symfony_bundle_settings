<?php


namespace DigipolisGent\SettingBundle\Tests\DataFixtures\ORM;


use DigipolisGent\SettingBundle\DataFixtures\ORM\LoadEntityTypes;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\Service\EntityTypeCollector;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Foo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadEntityTypesTest extends TestCase
{

    public function testLoad()
    {
        $entityTypes = [
            'foo' => Foo::class
        ];

        $entityTypeCollector = $this->getEntityTypeCollectorMock($entityTypes);
        $container = $this->getContainerMock($entityTypeCollector);

        $settingEntityType = new SettingEntityType();
        $settingEntityType->setName('bar');

        $settingEntityTypes = new ArrayCollection();
        $settingEntityTypes->add($settingEntityType);

        $repositoryMock = $this->getRepositoryMock($settingEntityTypes);

        $entityManager = $this->getEntityManagerMock($repositoryMock);

        $fixture = new LoadEntityTypes();
        $fixture->setContainer($container);

        $fixture->load($entityManager);
    }

    private function getRepositoryMock($settingEntityTypes)
    {
        $mock = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('findOneBy')
            ->willReturn(null);

        $mock
            ->expects($this->at(1))
            ->method('findAll')
            ->willReturn($settingEntityTypes);

        return $mock;
    }

    private function getEntityManagerMock($repository)
    {
        $mock = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('getRepository')
            ->with($this->equalTo(SettingEntityType::class))
            ->willReturn($repository);

        return $mock;
    }

    private function getEntityTypeCollectorMock($entityTypes)
    {
        $mock = $this
            ->getMockBuilder(EntityTypeCollector::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('getEntityTypes')
            ->willReturn($entityTypes);

        return $mock;
    }

    private function getContainerMock($entityTypeCollector)
    {
        $mock = $this
            ->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo(EntityTypeCollector::class))
            ->willReturn($entityTypeCollector);

        return $mock;
    }

}