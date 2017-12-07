<?php


namespace DigipolisGent\SettingBundle\Service;


use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\Entity\Traits\SettingImplementationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class FormService
 * @package DigipolisGent\SettingBundle\Service
 */
class FormService
{

    private $entityManager;
    private $fieldTypeServiceCollector;
    private $entityTypeCollector;

    public function __construct(
        EntityManagerInterface $entityManager,
        FieldTypeServiceCollector $fieldTypeServiceCollector,
        EntityTypeCollector $entityTypeCollector
    ) {
        $this->entityManager = $entityManager;
        $this->fieldTypeServiceCollector = $fieldTypeServiceCollector;
        $this->entityTypeCollector = $entityTypeCollector;
    }

    public function addConfig(Form $form)
    {
        $entity = $form->getData();
        $class = get_class($entity);

        $entityTypeName = $this->entityTypeCollector->getEntityTypeByClass($class);

        $entityType = $this->entityManager->getRepository(SettingEntityType::class)
            ->findOneBy(['name' => $entityTypeName]);

        if (is_null($entityType)) {
            return;
        }

        $settingDataTypes = $entityType->getSettingDataTypes()->toArray();
        usort($settingDataTypes, function ($a, $b) {
            return $a->getOrder() > $b->getOrder();
        });

        foreach ($settingDataTypes as $settingDataType) {
            $fieldTypeService = $this->fieldTypeServiceCollector->getFieldTypeService($settingDataType->getFieldType());

            $settingDataValue = $this->entityManager->getRepository(SettingDataValue::class)->findOneByKey($entity,
                $settingDataType->getKey());


            $options = [
                'label' => $settingDataType->getLabel(),
                'required' => $settingDataType->isRequired(),
                'mapped' => false,
            ];

            $value = $settingDataValue ? $settingDataValue->getValue() : '';

            $options = array_merge($options, $fieldTypeService->getOptions($value));

            $form->add(
                'config:' . $settingDataType->getKey(),
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

        if (!in_array(SettingImplementationTrait::class, class_uses($entity))) {
            return;
        }

        $entity->clearSettingDataValues();

        foreach ($form->getIterator() as $formElement) {
            if (strpos($formElement->getName(), 'config:') === false) {
                continue;
            }
            $settingDataTypeKey = str_replace('config:', '', $formElement->getName());

            $settingDataType = $this->entityManager->getRepository(SettingDataType::class)
                ->findOneBy(['key' => $settingDataTypeKey]);

            $fieldTypeService = $this->fieldTypeServiceCollector->getFieldTypeService($settingDataType->getFieldType());

            $value = $formElement->getData() ? $fieldTypeService->encodeValue($formElement->getData()) : '';

            $settingDataValue = new SettingDataValue();
            $settingDataValue->setSettingDataType($settingDataType);
            $settingDataValue->setValue($value);

            $entity->addSettingDataValue($settingDataValue);
        };

        return $entity;
    }
}