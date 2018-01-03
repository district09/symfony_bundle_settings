<?php


namespace DigipolisGent\SettingBundle\FieldType;

/**
 * Class DisabledIntegerFieldType
 * @package DigipolisGent\SettingBundle\FieldType
 */
class DisabledIntegerFieldType extends IntegerFieldType
{

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'disabled_integer';
    }

    /**
     * @param $value
     * @return array
     */
    public function getOptions($value): array
    {
        $options = parent::getOptions($value);
        $options['attr']['disabled'] = true;
        return $options;
    }

}