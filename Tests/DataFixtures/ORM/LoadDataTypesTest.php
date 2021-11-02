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

        $fixture = new LoadDataTypes(new DataTypeCollector());

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

        $settingDataType = new SettingDataType();
        $settingDataType->setKey('baz_qux');

        $settingDataTypes = new ArrayCollection();
        $settingDataTypes->add($settingDataType);


        $settingDataTypeRepository = $this->getSettingDataTypeRepositoryMock($settingDataTypes);

        $entityType = new SettingEntityType();
        $entityType->setName('foo');

        $settingEntityTypeRepository = $this->getSettingEntityTypeRepositoryMock($entityType);

        $entityManager = $this->getEntityManagerMock($settingDataTypeRepository, $settingEntityTypeRepository);

        $fixture = new LoadDataTypes($dataTypeCollector);
        $fixture->load($entityManager);
    }

    private function getSettingDataTypeRepositoryMock($settingDataTypes)
    {
        $mock = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('findOneBy')
            ->with($this->equalTo(['key' => 'foo_bar']))
            ->willReturn(null);

        $mock
            ->expects($this->any())
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
            ->expects($this->any())
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
            ->expects($this->any())
            ->method('getDataTypes')
            ->willReturn($dataTypes);

        return $mock;
    }

    private function getEntityManagerMock($settingDataTypeRepository, $settingEntityTypeRepository)
    {
        $mock = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturnMap([
                [SettingDataType::class, $settingDataTypeRepository],
                [SettingEntityType::class, $settingEntityTypeRepository]
            ]);;

        return $mock;
    }
}
