<?php


namespace DigipolisGent\SettingBundle\Tests\DataFixtures\ORM;


use DigipolisGent\SettingBundle\DataFixtures\ORM\LoadDataTypes;
use DigipolisGent\SettingBundle\DataFixtures\ORM\LoadEntityTypes;
use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\Service\DataTypeCollector;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadDataTypesTest extends TestCase
{
    public function testDepencies()
    {
        $expected = [
            LoadEntityTypes::class
        ];

        $fixture = new LoadDataTypes();

        $this->assertEquals($expected, $fixture->getDependencies());
    }

    public function testLoad()
    {
        $dataTypes = [
            [
                'key' => 'foo_bar',
                'label' => 'My label',
                'required' => true,
                'field_type' => 'string',
                'entity_types' => ['foo'],
            ]
        ];

        $dataTypeCollector = $this->getDataTypeCollectorMock($dataTypes);
        $container = $this->getContainerMock($dataTypeCollector);

        $settingDataType = new SettingDataType();
        $settingDataType->setKey('baz_qux');

        $settingDataTypes = new ArrayCollection();
        $settingDataTypes->add($settingDataType);


        $settingDataTypeRepository = $this->getSettingDataTypeRepositoryMock($settingDataTypes);

        $entityType = new SettingEntityType();
        $entityType->setName('foo');

        $settingEntityTypeRepository = $this->getSettingEntityTypeRepositoryMock($entityType);

        $entityManager = $this->getEntityManagerMock($settingDataTypeRepository, $settingEntityTypeRepository);

        $fixture = new LoadDataTypes();
        $fixture->setContainer($container);
        $fixture->load($entityManager);
    }

    private function getSettingDataTypeRepositoryMock($settingDataTypes)
    {
        $mock = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('findOneBy')
            ->with($this->equalTo(['key' => 'foo_bar']))
            ->willReturn(null);

        $mock
            ->expects($this->at(1))
            ->method('findAll')
            ->willReturn($settingDataTypes);

        return $mock;
    }

    private function getSettingEntityTypeRepositoryMock($settingEntityType)
    {
        $mock = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('findOneBy')
            ->with($this->equalTo(['name' => 'foo']))
            ->willReturn($settingEntityType);

        return $mock;
    }

    private function getDataTypeCollectorMock($dataTypes)
    {
        $mock = $this
            ->getMockBuilder(DataTypeCollector::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('getDataTypes')
            ->willReturn($dataTypes);

        return $mock;
    }

    private function getContainerMock($dataTypeCollector)
    {
        $mock = $this
            ->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo(DataTypeCollector::class))
            ->willReturn($dataTypeCollector);

        return $mock;
    }

    private function getEntityManagerMock($settingDataTypeRepository, $settingEntityTypeRepository)
    {
        $mock = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('getRepository')
            ->with($this->equalTo(SettingDataType::class))
            ->willReturn($settingDataTypeRepository);

        $mock
            ->expects($this->at(1))
            ->method('getRepository')
            ->with($this->equalTo(SettingEntityType::class))
            ->willReturn($settingEntityTypeRepository);

        return $mock;
    }
}