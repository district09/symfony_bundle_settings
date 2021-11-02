<?php


namespace DigipolisGent\SettingBundle\Tests\DataFixtures\ORM;


use DigipolisGent\SettingBundle\DataFixtures\ORM\LoadEntityTypes;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\Service\EntityTypeCollector;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Bar;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Foo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use PHPUnit\Framework\TestCase;

class LoadEntityTypesTest extends TestCase
{

    public function testLoad()
    {
        $settingEntityType = new SettingEntityType();
        $settingEntityType->setName('bar');

        $settingEntityTypes = new ArrayCollection();
        $settingEntityTypes->add($settingEntityType);

        $repositoryMock = $this->getRepositoryMock($settingEntityTypes);

        $metadata = [
            $this->getMetadataMock(Foo::class),
            $this->getMetadataMock(Bar::class),
        ];

        $metadataFactoryMock = $this->getMetadataFactoryMock($metadata);

        $entityManager = $this->getEntityManagerMock($repositoryMock, $metadataFactoryMock);

        $fixture = new LoadEntityTypes();

        $fixture->load($entityManager);
    }

    private function getRepositoryMock($settingEntityTypes)
    {
        $mock = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('findOneBy')
            ->willReturn(null);

        $mock
            ->expects($this->any())
            ->method('findAll')
            ->willReturn($settingEntityTypes);

        return $mock;
    }

    private function getMetadataMock($className)
    {
        $mock = $this
            ->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getName')
            ->willReturn($className);

        return $mock;
    }

    private function getMetadataFactoryMock($metadata)
    {
        $mock = $this
            ->getMockBuilder(ClassMetadataFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getAllMetadata')
            ->willReturn($metadata);

        return $mock;
    }

    private function getEntityManagerMock($repository, $metadataFactory)
    {
        $mock = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(SettingEntityType::class))
            ->willReturn($repository);

        $mock
            ->expects($this->any())
            ->method('getMetadataFactory')
            ->willReturn($metadataFactory);

        return $mock;
    }

}
