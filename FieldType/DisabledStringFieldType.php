<?php


namespace DigipolisGent\SettingBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

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
        $options['attr']['disabled'] = true;
        return $options;
    }

}