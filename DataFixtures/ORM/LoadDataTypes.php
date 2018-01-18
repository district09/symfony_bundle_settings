<?php


namespace DigipolisGent\SettingBundle\DataFixtures\ORM;


use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\Service\DataTypeCollector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadDataTypes
 * @package AppBundle\DataFixtures\ORM
 */
class LoadDataTypes extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $settingDataTypeRepository = $manager->getRepository(SettingDataType::class);

        $dataTypeCollector = $this->container->get(DataTypeCollector::class);
        $dataTypeKeys = [];

        foreach ($dataTypeCollector->getDataTypes() as $dataTypeArr) {

            $key = $dataTypeArr['key'];
            $label = $dataTypeArr['label'];
            $required = $dataTypeArr['required'];
            $fieldType = $dataTypeArr['field_type'];
            $entityTypeNames = $dataTypeArr['entity_types'];
            $order = isset($dataTypeArr['order']) ? $dataTypeArr['order'] : null;

            $dataTypeKeys[] = $key;

            $dataType = $settingDataTypeRepository->findOneBy(['key' => $key]);

            if (is_null($dataType)) {
                $dataType = new SettingDataType();
                $dataType->setKey($key);
            }

            $dataType->setLabel($label);
            $dataType->setRequired($required);
            $dataType->setFieldType($fieldType);
            $dataType->setOrder($order);

            $dataType->clearSettingEntityTypes();

            foreach ($entityTypeNames as $entityTypeName) {
                $entityType = $manager->getRepository(SettingEntityType::class)
                    ->findOneBy(['name' => $entityTypeName]);

                $dataType->addSettingEntityType($entityType);
            }

            $manager->persist($dataType);
        }

        $dataTypes = $settingDataTypeRepository->findAll();
        foreach ($dataTypes as $dataType) {
            if (!in_array($dataType->getKey(), $dataTypeKeys)) {
                $manager->remove($dataType);
            }
        }

        $manager->flush();
    }


    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            LoadEntityTypes::class,
        ];
    }
}