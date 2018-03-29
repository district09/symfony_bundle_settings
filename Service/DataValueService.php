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
    private $serviceCollector;

    /**
     * DataValueService constructor.
     * @param EntityManagerInterface $entityManager
     * @param FieldTypeServiceCollector $serviceCollector
     * @param ContainerInterface $container
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FieldTypeServiceCollector $serviceCollector
    ) {
        $this->entityManager = $entityManager;
        $this->serviceCollector = $serviceCollector;
    }

    /**
     * @param $entity
     * @param $key
     * @return null
     */
    public function getValue($entity, $key)
    {
        $dataValueRepository = $this->entityManager->getRepository(SettingDataValue::class);

        $settingDataValue = $dataValueRepository->findOneByKey($entity, $key);

        if (!$settingDataValue) {
            return null;
        }

        $fieldType = $settingDataValue->getSettingDataType()->getFieldType();
        $fieldTypeService = $this->serviceCollector->getFieldTypeService($fieldType);

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
        $dataValueRepository = $this->entityManager->getRepository(SettingDataValue::class);

        $settingDataValue = $dataValueRepository->findOneByKey($entity, $key);

        if (!$settingDataValue) {
            $dataTypeRepository = $this->entityManager->getRepository(SettingDataType::class);
            $settingDataType = $dataTypeRepository->findOneBy(['key' => $key]);

            $settingDataValue = new SettingDataValue();
            $settingDataValue->setSettingDataType($settingDataType);

            $entity->addSettingDataValue($settingDataValue);
        }

        $fieldType = $settingDataValue->getSettingDataType()->getFieldType();
        $fieldTypeService = $this->serviceCollector->getFieldTypeService($fieldType);

        $settingDataValue->setValue($fieldTypeService->encodeValue($value));

        $this->entityManager->persist($settingDataValue);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $settingDataValue;
    }
}
