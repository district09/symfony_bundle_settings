<?php


namespace DigipolisGent\SettingBundle\FieldType;

/**
 * Class DisabledStringFieldType
 * @package DigipolisGent\SettingBundle\FieldType
 */
class DisabledStringFieldType extends StringFieldType
{

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'disabled_string';
    }

    /**
     * @param $value
     * @return array
     */
    public function getOptions($value): array
    {
        $options = parent::getOptions($value);
        // Do not use the disabled attribute because it will remove the existing
        // value.
        $options['attr']['readonly'] = true;
        return $options;
    }
}
