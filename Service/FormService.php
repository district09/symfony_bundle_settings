<?php


namespace DigipolisGent\SettingBundle\Service;


use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
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

    public function addConfig(FormBuilderInterface $builder)
    {
        $configFieldBuilder = $builder->create('config', FormType::class, ['mapped' => false,'label' => false]);
        $entity = $builder->getData();

        $entityType = $this->entityManager->getRepository(SettingEntityType::class)
            ->findOneBy(['name' => $this->entityTypeCollector->getEntityTypeByClass(get_class($entity))]);

        $settingDataTypes = $entityType->getSettingDataTypes()->toArray();
        usort($settingDataTypes,function ($a,$b){
           return $a->getOrder() > $b->getOrder();
        });

        foreach ($settingDataTypes as $settingDataType) {
            $fieldTypeService = $this->fieldTypeServiceCollector->getFieldTypeService($settingDataType->getFieldType());

            $callbackConstraint = function ($value, ExecutionContextInterface $context) use ($fieldTypeService) {
                $errorMessages = $fieldTypeService->validate($value);
                foreach ($errorMessages as $errorMessage) {
                    $context->addViolation($errorMessage);
                }
            };

            $settingDataValue = $this->entityManager->getRepository(SettingDataValue::class)->findOneByKey($entity,
                $settingDataType->getKey());

            $configFieldBuilder->add(
                $settingDataType->getKey(),
                $fieldTypeService->getFormType(),
                [
                    'label' => $settingDataType->getLabel(),
                    'required' => $settingDataType->isRequired(),
                    'constraints' => [
                        new Callback($callbackConstraint),
                    ],
                    'attr' => $fieldTypeService->getFormAttributes($settingDataValue->getValue()),
                ]
            );
        }

        $builder->add($configFieldBuilder);
    }

    /**
     * @param Form $form
     * @return mixed
     */
    public function processForm(Form $form)
    {
        $entity = $form->getData();
        $configData = $form->get('config')->getData();
        $entity->clearSettingDataValues();

        foreach ($configData as $settingDataTypeKey => $value) {
            $settingDataType = $this->entityManager->getRepository(SettingDataType::class)
                ->findOneBy(['key' => $settingDataTypeKey]);

            $settingDataValue = new SettingDataValue();
            $settingDataValue->setSettingDataType($settingDataType);
            $settingDataValue->setValue($value);

            $entity->addSettingDataValue($settingDataValue);
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }
}