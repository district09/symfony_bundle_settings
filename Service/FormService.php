<?php


namespace DigipolisGent\SettingBundle\Service;

use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\Entity\Traits\SettingImplementationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;

/**
 * Class FormService
 * @package DigipolisGent\SettingBundle\Service
 */
class FormService
{

    private $entityManager;
    private $serviceCollector;

    public function __construct(
        EntityManagerInterface $entityManager,
        FieldTypeServiceCollector $serviceCollector
    ) {
        $this->entityManager = $entityManager;
        $this->serviceCollector = $serviceCollector;
    }

    public function addConfig(Form $form)
    {
        $entity = $form->getData();
        $class = get_class($entity);

        if (!in_array(SettingImplementationTrait::class, class_uses($class))) {
            return;
        }

        $entityTypeName = $class::getSettingImplementationName();
        $entityType = $this->entityManager->getRepository(SettingEntityType::class)
            ->findOneBy(['name' => $entityTypeName]);

        $settingDataTypes = $entityType->getSettingDataTypes()->toArray();

        usort($settingDataTypes, function ($dta, $dtb) {
            return $dta->getOrder() > $dtb->getOrder();
        });

        foreach ($settingDataTypes as $settingDataType) {
            $fieldTypeService = $this->serviceCollector->getFieldTypeService($settingDataType->getFieldType());
            $fieldTypeService->setOriginEntity($entity);

            $settingDataValue = $this->entityManager->getRepository(SettingDataValue::class)
                ->findOneByKey($entity, $settingDataType->getKey());

            $options = [
                'label' => $settingDataType->getLabel(),
                'required' => $settingDataType->isRequired(),
                'mapped' => false,
            ];

            $value = $settingDataValue ? $settingDataValue->getValue() : '';

            $options = array_merge($options, $fieldTypeService->getOptions($value));

            $form->add(
                'config_' . $settingDataType->getKey(),
                $fieldTypeService->getFormType(),
                $options
            );
        }
    }

    /**
     * @param Form $form
     * @return mixed
     */
    public function processForm(Form $form)
    {
        $entity = $form->getData();

        $class = get_class($entity);

        $parentClass = get_parent_class($class);
        if ($parentClass) {
            $class = $parentClass;
        }

        if (!in_array(SettingImplementationTrait::class, class_uses($class))) {
            return;
        }

        $entity->clearSettingDataValues();

        foreach ($form->getIterator() as $formElement) {
            if (strpos($formElement->getName(), 'config_') === false) {
                continue;
            }

            $settingDataTypeKey = str_replace('config_', '', $formElement->getName());

            $settingDataType = $this->entityManager->getRepository(SettingDataType::class)
                ->findOneBy(['key' => $settingDataTypeKey]);

            $fieldTypeService = $this->serviceCollector->getFieldTypeService($settingDataType->getFieldType());
            $fieldTypeService->setOriginEntity($entity);

            $value = $formElement->getData() ? $fieldTypeService->encodeValue($formElement->getData()) : '';

            $settingDataValue = new SettingDataValue();
            $settingDataValue->setSettingDataType($settingDataType);
            $settingDataValue->setValue($value);

            $entity->addSettingDataValue($settingDataValue);
        };

        return $entity;
    }
}
