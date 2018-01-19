# Digipolis Symfony Setting Bundle

## Introduction

This library allows you to add extra values for instances of predefined entity types.
These extra values and their characteristics are dynamicly defined in other bundles with the usage of data providers.
Based on these providers your form will automaticly build and do the necessary validations.
After saving, these values (strings, integers, array collections, ...) are easily accessible with the services we provide in this bundle so they can be used troughout the project.

## Compatibility

This bundle is compatible with all Symfony 3.4.* releases.

## Installation

You can use composer to install the bundle in an existing symfony project.

```
composer require digipolisgent/setting-bundle
```

Then, update your ```app/AppKernel.php``` file.

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new DigipolisGent\SettingBundle\DigipolisGentSettingBundle(),
    ];

    // ...
}
```

There are no routes to register or other mandatory configuration options.

## Before reading this documentation

If you are not familiar with symfony we suggest reading the [symfony 3.4 documentation](https://symfony.com/doc/3.4/index.html).

## Entity types

Entity types are the the entities we want to assign extra values to. We define these by using the SettingImplementationTrait.
This requires the ```getSettingImplementationName``` method to be implemented to.

```php
<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DigipolisGent\SettingBundle\Entity\Traits\SettingImplementationTrait;

/**
* @ORM\Entity() 
*/
class Foo
{
 
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    use SettingImplementationTrait;
    
    /**
     * @return string
     */
    public static function getSettingImplementationName()
    {
        return 'foo';
    }  
}
```

The trait will

- Make sure the database is updated using the ```DynamicSettingImplementationRelationSubscriber```
- A ```SettingEntityType``` entity is added to the database
- Extra data can be added to the entity using the ```DataValueService```
- The ```FormService``` adds and processes the extra configuration if the set data is an entity using this trait
 
## Field types

These are the the services that will define how an extra configured field behaves in a form and how the data is presented when we want to use it trougout the application.

A service needs to extend from the ```AbstractFieldType```. As an example we use one of the predefined field types.

```php
<?php


namespace DigipolisGent\SettingBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class StringFieldType
 * @package DigipolisGent\SettingBundle\FieldType
 */
class StringFieldType extends AbstractFieldType
{

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'string';
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return TextType::class;
    }

    /**
     * @param $value
     * @return array
     */
    public function getOptions($value): array
    {
        $options = [];
        $options['attr']['value'] = $value ? $value : '';
        return $options;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function decodeValue($value)
    {
        return $value;
    }

    /**
     * @param $value
     * @return string
     */
    public function encodeValue($value): string
    {
        return $value;
    }
}
```

The important thing here is the name we give to the service. We will use this name later to add extra config to the entities that are an instance of the entity types we used before.

Once the field type is in its place we define the service and tag it as a ```field_type```

```yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true
        public: true
    DigipolisGent\SettingBundle\FieldType\StringFieldType:
        tags:
            - { name: field_type}
```

## Data types

Data types are the representation of the extra settings you want to add for every entity type. You can define these by adding a ```DataTypeProvider```.
These datatype providers must be registered as a tagged service using the ```data_type_provider``` tag and implement the ```DataTypeProviderInterface```.

```php
<?php


namespace AppBundle\Provider;

use DigipolisGent\SettingBundle\Provider\DataTypeProviderInterface;

class DataTypeProvider implements DataTypeProviderInterface
{

    /**
     * @return array
     */
    public function getDataTypes()
    {
        return [
            [
                'key' => 'bar',
                'label' => 'My foo label',
                'required' => true,
                'field_type' => 'foo',
                'entity_types' => ['foo'],
            ],
        ];
    }
}
```

All keys are checked 

- key : this is the key you can access the extra setting with if you need it at a later yime
- label : this is the label that will be used in forms
- field_type: this is the name of the field type that where defined previously
- entity_types: this is a list of all entity type names that you want this to be available to


## Loading data types and entity types

For the changes to take effect the database needs to be updated. The following command will fill the database.

```bash
bin/console doctrine:fixtures:load --append
```

## Form building

You can these extra settings to your form by adding an event subscriber to your form builder.
This subscriber will make sure the form is build and processed as defined in the field types.
If the data set to the form is not an entity type nothing will happen. This way you can also use the subscriber in generic forms.

```php
<?php


namespace AppBundle\Form\Type;


use DigipolisGent\SettingBundle\EventListener\SettingFormListener;
use DigipolisGent\SettingBundle\Service\FormService;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;

class YourFormType extends AbstractType
{

    public $formService;

    /**
     * @param FormService $formService
     */
    public function setFormService(FormService $formService)
    {
        $this->formService = $formService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->addEventSubscriber(new SettingFormListener($this->formService));
    }
}

```

## Data value service

To access these values in other scripts we can use the ```DataValueService```.

For example. If you want the the value with key ```bar``` from an instance of the ```foo``` class that you previously saved using the form you do the following.

```php
$value = $this->dataValueService->getValue($foo, 'bar');
```

If after some data manipulation you want to update this value and make it visibile in the form you can also store the value.


```php
$this->dataValueService->storeValue($foo, 'bar', 'manipulated string');
```

## Advanced field type usage

You can also use the field type to store and manipulate entities since you can inject other services here. This is an example where we make a checkbox list of ```bar``` entities.

```php
<?php


namespace AppBundle\FieldType;


use AppBundle\Entity\Bar;
use AppBundle\Form\Type\BarFormType;
use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\FieldType\AbstractFieldType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BarCheckboxFieldType extends AbstractFieldType
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFormType(): string
    {
        return CollectionType::class;
    }

    /**
     * @param $value
     * @return array
     */
    public function getOptions($value): array
    {
        $options = [];
        $options['entry_type'] = BarFormType::class;
        $options['allow_add'] = true;
        $options['allow_delete'] = true;
        $options['by_reference'] = false;
        $options['prototype'] = true;
        $options['prototype_data'] = new Bar();

        $ids = json_decode($value, true);

        $barRepository = $this->entityManager->getRepository(Bar::class);

        $data = [];

        if (!is_null($ids)) {
            foreach ($ids as $id) {
                $data[] = $barRepository->find($id);
            }
        }
        
        $options['data'] = $data;

        return $options;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'bar_checkbox';
    }

    /**
     * @param $value
     * @return string
     */
    public function encodeValue($value): string
    {
        $barIds = [];

        foreach ($value as $bar) {
            $this->entityManager->persist($bar);
            $capistranoSymlinkIds[] = $bar->getId();
        }

        return json_encode($barIds);
    }

    public function decodeValue($value)
    {
        $barRepository = $this->entityManager->getRepository(Bar::class);

        $ids = [];

        if ($value == '' || is_null($ids)) {
            return [];
        }

        $bars = [];
        $ids = json_decode($value, true);

        foreach ($ids as $id) {
            $bars[] = $barRepository->find($id);
        }

        return $bars;
    }
}

```

Using ```bar_checkbox``` as a fielf type in your data type provider will :

- Give a list of checkboxes with all bar entities when you generate a form
- The ```getValue``` function in the ```DataValueService``` will give you a list of ```Bar``` entities
- The ```storeValue``` function in the ```DataValueService``` gives you the possibility to save a list of ```Bar``` entities

## Advantages
 
- You can have extra properties for the entities that appear and disappear if you activate or deactivate bundles. 
- The field types give the possibility to reuse code multiple times.
- You can change the structure in a mather of seconds by changing the dataprovider array










