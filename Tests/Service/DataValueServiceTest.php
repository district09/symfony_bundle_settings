<?php


namespace DigipolisGent\SettingBundle\Tests\Service;


use DigipolisGent\SettingBundle\Entity\Repository\SettingDataValueRepository;
use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\FieldType\StringFieldType;
use DigipolisGent\SettingBundle\Service\DataValueService;
use DigipolisGent\SettingBundle\Service\FieldTypeServiceCollector;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Foo;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class DataValueServiceTest extends TestCase
{

    public function testGetValue()
    {
        $settingEntityType = new SettingEntityType();
        $settingEntityType->setName('foo');

        $settingDataType = new SettingDataType();
        $settingDataType->addSettingEntityType($settingEntityType);
        $settingDataType->setFieldType('string');
        $settingDataType->setKey('foo_string_key');

        $settingDataValue = new SettingDataValue();
        $settingDataValue->setValue('foo_value');
        $settingDataValue->setSettingDataType($settingDataType);

        $entity = new Foo();
        $entity->addSettingDataValue($settingDataValue);

        $repository = $this->getSettingDataValueRepositoryMock($settingDataValue);
        $entityManager = $this->getEntityManagerMock($repository);
        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollector();

        $fieldTypeServiceCollector
            ->expects($this->at(0))
            ->method('getFieldTypeService')
            ->with($this->equalTo('string'))
            ->willReturn(new StringFieldType());

        $dataValueService = new DataValueService(
            $entityManager,
            $fieldTypeServiceCollector
        );

        $value = $dataValueService->getValue($entity, 'foo_string_key');
        $this->assertEquals('foo_value', $value);
    }

    public function testGetEmptyValue()
    {
        $settingEntityType = new SettingEntityType();
        $settingEntityType->setName('foo');

        $entity = new Foo();

        $repository = $this->getSettingDataValueRepositoryMock(null);
        $entityManager = $this->getEntityManagerMock($repository);
        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollector(new StringFieldType());

        $dataValueService = new DataValueService(
            $entityManager,
            $fieldTypeServiceCollector
        );

        $value = $dataValueService->getValue($entity, 'random_key');
        $this->assertNull($value);
    }

    public function testStoreValue()
    {
        $settingEntityType = new SettingEntityType();
        $settingEntityType->setName('foo');

        $settingDataType = new SettingDataType();
        $settingDataType->addSettingEntityType($settingEntityType);
        $settingDataType->setFieldType('string');
        $settingDataType->setKey('foo_string_key');

        $settingDataValue = new SettingDataValue();
        $settingDataValue->setValue('foo_value');
        $settingDataValue->setSettingDataType($settingDataType);

        $entity = new Foo();
        $entity->addSettingDataValue($settingDataValue);

        $repository = $this->getSettingDataValueRepositoryMock($settingDataValue);
        $entityManager = $this->getEntityManagerMock($repository);
        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollector();

        $fieldTypeServiceCollector
            ->expects($this->at(0))
            ->method('getFieldTypeService')
            ->with($this->equalTo('string'))
            ->willReturn(new StringFieldType());

        $dataValueService = new DataValueService(
            $entityManager,
            $fieldTypeServiceCollector
        );

        $result = $dataValueService->storeValue($entity, 'foo_string_key', 'foo_update_value');
        $this->assertInstanceOf(SettingDataValue::class, $result);
        $this->assertEquals('foo_update_value', $result->getValue());
    }

    public function testStoreNonExistingValue()
    {
        $settingEntityType = new SettingEntityType();
        $settingEntityType->setName('foo');

        $entity = new Foo();

        $settingDataType = new SettingDataType();
        $settingDataType->addSettingEntityType($settingEntityType);
        $settingDataType->setFieldType('string');
        $settingDataType->setKey('foo_string_key');

        $repository = $this->getSettingDataValueRepositoryMock(null);
        $entityManager = $this->getEntityManagerMock($repository);
        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollector();

        $fieldTypeServiceCollector
            ->expects($this->at(0))
            ->method('getFieldTypeService')
            ->with($this->equalTo('string'))
            ->willReturn(new StringFieldType());

        $settingDataTypeRepository = $this->getSettingDataTypeRepositoryMock($settingDataType);

        $entityManager
            ->expects($this->at(1))
            ->method('getRepository')
            ->with($this->equalTo(SettingDataType::class))
            ->willReturn($settingDataTypeRepository);

        $dataValueService = new DataValueService(
            $entityManager,
            $fieldTypeServiceCollector
        );

        $result = $dataValueService->storeValue($entity, 'foo_string_key', 'foo_update_value');
        $this->assertInstanceOf(SettingDataValue::class, $result);
        $this->assertEquals('foo_update_value', $result->getValue());
    }

    private function getSettingDataValueRepositoryMock($settingDataValue)
    {
        $mock = $this
            ->getMockBuilder(SettingDataValueRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('findOneByKey')
            ->willReturn(
                $settingDataValue
            );

        return $mock;
    }

    private function getSettingDataTypeRepositoryMock($settingDataType)
    {
        $mock = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('findOneBy')
            ->willReturn(
                $settingDataType
            );

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
            ->with($this->equalTo(SettingDataValue::class))
            ->willReturn($repository);

        return $mock;
    }

    private function getFieldTypeServiceCollector()
    {
        $mock = $this
            ->getMockBuilder(FieldTypeServiceCollector::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

}