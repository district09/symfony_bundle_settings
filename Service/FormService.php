<?php


namespace DigipolisGent\SettingBundle\Service;

use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\Entity\Traits\SettingImplementationTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class FormService
 * @package DigipolisGent\SettingBundle\Service
 */
class FormService
{

    private $entityManager;
    private $serviceCollector;
    /**
     *
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        FieldTypeServiceCollector $serviceCollector,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->serviceCollector = $serviceCollector;
        $this->validator = $validator;
    }

    public function addConfig(Form $form)
    {
        $entity = $form->getData();
        if (!is_object($entity)) {
            return;
        }
        $class = get_class($entity);

        $parentClass = get_parent_class($class);
        if ($parentClass) {
            $class = $parentClass;
        }

        if (!in_array(SettingImplementationTrait::class, class_uses($class))) {
            return;
        }

        $entityTypeName = $class::getSettingImplementationName();
        $entityType = $this->entityManager->getRepository(SettingEntityType::class)
            ->findOneBy(['name' => $entityTypeName]);

        $settingDataTypes = $entityType->getSettingDataTypes()->toArray();

        usort($settingDataTypes, function ($dta, $dtb) {
            return $dta->getOrder() - $dtb->getOrder();
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

            $defaultValue = $settingDataType->getDefaultValue();
            if($defaultValue){
                $options['data'] = $defaultValue;
            }

            $value = $settingDataValue ? $settingDataValue->getValue() : '';

            $options = array_merge($options, $fieldTypeService->getOptions($value));

            if($settingDataType->isRequired()){
                $options['constraints'][] = new NotBlank();
            }

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
            $this->validate($formElement);
            $formData = $formElement->getData();

            $value = $formData ? $fieldTypeService->encodeValue($formData) : '';

            $settingDataValue = new SettingDataValue();
            $settingDataValue->setSettingDataType($settingDataType);
            $settingDataValue->setValue($value);

            $entity->addSettingDataValue($settingDataValue);
        };

        $this->entityManager->persist($entity);

        return $entity;
    }

    protected function validate(FormInterface $formElement)
    {
        $formData = $formElement->getData();
        if (is_array($formData)) {
            foreach ($formData as $key => $data) {
                if (is_object($data) && strpos(get_class($data), '\\Entity\\') !== false) {
                    $errors = $this->validator->validate($data);
                    foreach ($errors as $error) {
                        $formElement
                            ->get($key)
                            ->get($error->getPropertyPath())
                            ->addError(new FormError($error->getMessage()));
                    }
                }
            }
        }
    }
}
