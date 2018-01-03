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
     * @return string
     */
    public function encodeValue($value): string
    {
        return $value;
    }
}