<?php


namespace DigipolisGent\SettingBundle\FieldType;

/**
 * Interface FieldTypeInterface
 * @package DigipolisGent\SettingBundle\FieldType
 */
interface FieldTypeInterface
{

    public function validate($value): array;

    public function getFormAttributes($value);

    public function getFormType(): string;

    public static function getName(): string;

}