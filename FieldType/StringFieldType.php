<?php


namespace DigipolisGent\SettingBundle\FieldType;

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
     * @return string
     */
    public static function getName(): string
    {
        return 'string';
    }

}