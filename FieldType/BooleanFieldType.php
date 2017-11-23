<?php


namespace DigipolisGent\SettingBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Class BooleanFieldType
 * @package DigipolisGent\SettingBundle\FieldType
 */
class BooleanFieldType implements FieldTypeInterface
{

    /**
     * @param $value
     * @return array
     */
    public function validate($value): array
    {
        $errorMessages = [];
        return $errorMessages;
    }


    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'boolean';
    }

    /**
     * @param $value
     */
    public function getFormAttributes($value)
    {
        $attributes = [];

        if ($value) {
            $attributes['checked'] = 'checked';
        }

        return $attributes;
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return CheckboxType::class;
    }
}