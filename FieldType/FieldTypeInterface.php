<?php


namespace DigipolisGent\SettingBundle\FieldType;

/**
 * Interface FieldTypeInterface
 * @package DigipolisGent\SettingBundle\FieldType
 */
interface FieldTypeInterface
{
    public function getFormType(): string;

    public function getOptions($value): array;

    public static function getName(): string;

    public function encodeValue($value): string;
}