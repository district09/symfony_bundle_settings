<?php


namespace DigipolisGent\SettingBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class StringFieldType
 * @package DigipolisGent\SettingBundle\FieldType
 */
class StringFieldType implements FieldTypeInterface
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
     * @param $value
     */
    public function getFormAttributes($value)
    {
        return [
            'value' => $value ? $value : '',
        ];
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
}