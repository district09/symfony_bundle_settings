<?php


namespace DigipolisGent\SettingBundle\Tests\Service;

use DigipolisGent\SettingBundle\Entity\Repository\SettingDataValueRepository;
use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\FieldType\StringFieldType;
use DigipolisGent\SettingBundle\Service\FieldTypeServiceCollector;
use DigipolisGent\SettingBundle\Service\FormService;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Bar;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Foo;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\FooParent;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Forms;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormServiceTest extends TestCase
{

    public function testUndefinedEntityTypeAddConfig()
    {
        $form = $this->getForm();

        $form->setData(
            new Bar()
        );

        $entityManager = $this->getEntityManagerMock([]);

        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollectorMock();
        $validator = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();

        $formService = new FormService(
            $entityManager,
            $fieldTypeServiceCollector,
            $validator
        );

        $formService->addConfig($form);
        $iterator = $form->getIterator();

        $this->assertFalse($form->getErrors()->hasChildren());
        $this->assertCount(0, $iterator);
    }

    public function testSettingDataTypesOrderAddConfig()
    {

        $form = $this->getForm();

        $form->setData(
            new Foo()
        );

        $settingEntityType = new SettingEntityType();
        $settingEntityType->setName('foo');

        $settingEntityType->addSettingDataType((new SettingDataType())->setOrder(0)->setKey('one')
            ->setFieldType('string')->setRequired(false)->setLabel('one'));
        $settingEntityType->addSettingDataType((new SettingDataType())->setOrder(2)->setKey('three')
            ->setFieldType('string')->setRequired(false)->setLabel('three'));
        $settingEntityType->addSettingDataType((new SettingDataType())->setOrder(1)->setKey('two')
            ->setFieldType('string')->setRequired(false)->setLabel('two'));

        $repositories = [
            [
                'class' => SettingEntityType::class,
                'returnValue' => $this->getSettingEntityTypeRepositoryMock(
                    $settingEntityType
                )
            ],
            [
                'class' => SettingDataValue::class,
                'returnValue' => $this->getSettingDataValueRepositoryMock(
                    $this->getSettingDataValue('one')
                )
            ],
            [
                'class' => SettingDataValue::class,
                'returnValue' => $this->getSettingDataValueRepositoryMock(
                    $this->getSettingDataValue('two')
                )
            ],
            [
                'class' => SettingDataValue::class,
                'returnValue' => $this->getSettingDataValueRepositoryMock(
                    $this->getSettingDataValue('three')
                )
            ],
        ];

        $entityManager = $this->getEntityManagerMock($repositories);

        $fieldTypeCollector = $this->getFieldTypeServiceCollectorMock([
            'string' => new StringFieldType(),
        ]);

        $validator = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();


        $formService = new FormService(
            $entityManager,
            $fieldTypeCollector,
            $validator
        );

        $formService->addConfig($form);
        $iterator = $form->getIterator();

        $this->assertFalse($form->getErrors()->hasChildren());
        $this->assertCount(3, $iterator);

        $keys = ['config_one', 'config_two', 'config_three'];

        $index = 0;
        foreach ($iterator as $form) {
            $this->assertEquals($keys[$index], $form->getName());
            $index++;
        }
    }

    public function testUndefinedEntityTypeProcessForm()
    {
        $form = $this->getForm();

        $form->setData(
            new Bar()
        );

        $entityManager = $this->getEntityManagerMock([]);
        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollectorMock();
        $validator = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();

        $formService = new FormService(
            $entityManager,
            $fieldTypeServiceCollector,
            $validator
        );

        $formService->processForm($form);
        $this->assertFalse($form->getErrors()->hasChildren());
    }

    public function testEntityTypeProcessFormWitoutConfig()
    {
        $form = $this->getForm();

        $form->setData(
            new FooParent()
        );

        $form->add(
            'not_a_valid_name',
            TextType::class,
            [
                'mapped' => false
            ]
        );

        $entityManager = $this->getEntityManagerMock([]);
        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollectorMock();
        $validator = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();

        $formService = new FormService(
            $entityManager,
            $fieldTypeServiceCollector,
            $validator
        );

        $formService->processForm($form);
        $this->assertCount(0, $form->getData()->getSettingDataValues());
        $this->assertFalse($form->getErrors()->hasChildren());
    }

    public function testEntityTypeProcessFormWithConfig()
    {
        $form = $this->getForm();

        $form->setData(
            new FooParent()
        );

        $settingDataType = new SettingDataType();
        $settingDataType->setKey('my_string_key');
        $settingDataType->setFieldType('string');
        $settingDataType->setRequired(true);
        $settingDataType->setOrder(0);
        $settingDataType->setLabel('My string label');

        $form->add(
            'config_my_string_key',
            TextType::class,
            [
                'mapped' => false,
                'required' => true,
            ]
        );

        $repositories = [
            [
                'class' => SettingDataType::class,
                'returnValue' => $this->getSettingDataTypeRepositoryMock(
                    $settingDataType
                )
            ],
        ];

        $entityManager = $this->getEntityManagerMock($repositories);

        $fieldTypeServices = [
            'string' => new StringFieldType()
        ];

        $fieldTypeServiceCollector = $this->getFieldTypeServiceCollectorMock($fieldTypeServices);
        $validator = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();


        $formService = new FormService(
            $entityManager,
            $fieldTypeServiceCollector,
            $validator
        );

        $result = $formService->processForm($form);
        $this->assertInstanceOf(FooParent::class, $result);
        $this->assertCount(1, $result->getSettingDataValues());
        $this->assertFalse($form->getErrors()->hasChildren());
    }


    private function getForm()
    {
        $factory = Forms::createFormFactoryBuilder()->getFormFactory();
        return $factory->create(FormType::class);
    }

    private function getEntityManagerMock(array $repositories)
    {
        $mock = $this
            ->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $index = 0;
        $map = [];
        foreach ($repositories as $repository) {
            $map[] = [$repository['class'], $repository['returnValue']];
        }
        $mock
            ->expects($this->any())
            ->method('getRepository')
            ->willReturnMap($map);

        return $mock;
    }

    private function getSettingDataValueRepositoryMock($settingDataValue)
    {
        $mock = $this
            ->getMockBuilder(SettingDataValueRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
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
            ->method('findOneBy')
            ->willReturn(
                $settingDataType
            );

        return $mock;
    }

    private function getSettingDataValue($value)
    {
        $settingDataValue = new SettingDataValue();
        $settingDataValue->setValue($value);

        return $settingDataValue;
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
            ->willReturn(
                $settingEntityType
            );

        return $mock;
    }

    private function getFieldTypeServiceCollectorMock(array $fieldTypeServices = array())
    {
        $mock = $this
            ->getMockBuilder(FieldTypeServiceCollector::class)
            ->disableOriginalConstructor()
            ->getMock();

        foreach ($fieldTypeServices as $name => $instance) {
            $mock
                ->method('getFieldTypeService')
                ->with($this->equalTo($name))
                ->willReturn($instance);
        }

        return $mock;
    }
}
