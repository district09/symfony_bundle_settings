<?php


namespace DigipolisGent\SettingBundle\Service;


use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DataValueService
 * @package DigipolisGent\SettingBundle\Service
 */
class DataValueService
{

    private $entityManager;
    private $fieldTypeServiceCollector;

    /**
     * DataValueService constructor.
     * @param EntityManagerInterface $entityManager
     * @param FieldTypeServiceCollector $fieldTypeServiceCollector
     * @param ContainerInterface $container
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FieldTypeServiceCollector $fieldTypeServiceCollector
    ) {
        $this->entityManager = $entityManager;
        $this->fieldTypeServiceCollector = $fieldTypeServiceCollector;
    }

    /**
     * @param $entity
     * @param $key
     * @return null
     */
    public function getValue($entity, $key)
    {
        $settingDataValueRepository = $this->entityManager->getRepository(SettingDataValue::class);

        $settingDataValue = $settingDataValueRepository->findOneByKey($entity, $key);

        if (!$settingDataValue) {
            return null;
        }

        $fieldType = $settingDataValue->getSettingDataType()->getFieldType();
        $fieldTypeService = $this->fieldTypeServiceCollector->getFieldTypeService($fieldType);

        return $fieldTypeService->decodeValue($settingDataValue->getValue());
    }

    /**
     * @param $entity
     * @param $key
     * @param $value
     * @throws \Exception
     */
    public function storeValue($entity, $key, $value)
    {
        $settingDataValueRepository = $this->entityManager->getRepository(SettingDataValue::class);

        $settingDataValue = $settingDataValueRepository->findOneByKey($entity, $key);

        if (!$settingDataValue) {
            $settingDataTypeRepository = $this->entityManager->getRepository(SettingDataType::class);
            $settingDataType = $settingDataTypeRepository->findOneBy(['key' => $key]);

            $settingDataValue = new SettingDataValue();
            $settingDataValue->setSettingDataType($settingDataType);

            $entity->addSettingDataValue($settingDataValue);
        }

        $fieldType = $settingDataValue->getSettingDataType()->getFieldType();
        $fieldTypeService = $this->fieldTypeServiceCollector->getFieldTypeService($fieldType);

        $settingDataValue->setValue($fieldTypeService->encodeValue($value));

        $this->entityManager->persist($entity);
        $this->entityManager->persist($settingDataValue);
        $this->entityManager->flush();

        return $settingDataValue;
    }

}