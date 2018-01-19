<?php


namespace DigipolisGent\SettingBundle\Tests\Repository;


use DigipolisGent\SettingBundle\Entity\Repository\SettingDataValueRepository;
use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Foo;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\FooParent;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\NamingStrategy;
use PHPUnit\Framework\TestCase;

class SettingDataValueRepositoryTest extends TestCase
{

    public function testFindOneByKeyWithNoResult()
    {
        $entityManager = $this->getEntityManagerMock(null);
        $classMetadata = $this->getClassMetadataMock();

        $repository = new SettingDataValueRepository($entityManager, $classMetadata);

        $entity = new Foo();

        $result = $repository->findOneByKey($entity, 'non_existing_key');
        $this->assertNull($result);
    }

    public function testFindOneByKeyWithResult()
    {
        $settingDataType = new SettingDataType();
        $settingDataType->setLabel('My label');
        $settingDataType->setOrder(0);
        $settingDataType->setRequired(true);
        $settingDataType->setKey('existing_key');
        $settingDataType->setFieldType('string');

        $settingDataValue = new SettingDataValue();
        $settingDataValue->setValue('my_value');
        $settingDataValue->setSettingDataType($settingDataType);

        $entity = new FooParent();
        $entity->addSettingDataValue($settingDataValue);

        $entityManager = $this->getEntityManagerMock($settingDataValue);
        $classMetadata = $this->getClassMetadataMock();

        $repository = new SettingDataValueRepository($entityManager, $classMetadata);

        $result = $repository->findOneByKey($entity, 'existing_key');
        $this->assertInstanceOf(SettingDataValue::class, $result);
        $this->assertEquals('my_value', $result->getValue());
    }

    private function getEntityManagerMock($settingDataValue)
    {
        $mock = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->method('getConfiguration')
            ->willReturn($this->getConfigurationMock());

        $mock
            ->method('getClassMetadata')
            ->willReturn($this->getClassMetadataMock());

        $mock
            ->method('getConnection')
            ->willReturn($this->getConnectionMock());

        $mock
            ->method('createNativeQuery')
            ->willReturn($this->getNativeQueryMock($settingDataValue));

        return $mock;
    }

    private function getConfigurationMock()
    {
        $mock = $this
            ->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->method('getNamingStrategy')
            ->willReturn($this->getNamingStrategyMock());

        return $mock;
    }

    private function getNamingStrategyMock()
    {
        $mock = $this
            ->getMockBuilder(NamingStrategy::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->method('classToTableName')
            ->willReturn('foo');

        return $mock;
    }

    private function getClassMetadataMock()
    {
        $mock = $this
            ->getMockBuilder(ClassMetadata::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->method('getColumnNames')
            ->willReturn([]);

        return $mock;
    }

    private function getConnectionMock()
    {
        $mock = $this
            ->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    private function getNativeQueryMock($settingDataValue)
    {
        $mock = $this
            ->getMockBuilder(AbstractQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->method('getOneOrNullResult')
            ->willReturn($settingDataValue);

        return $mock;
    }
}