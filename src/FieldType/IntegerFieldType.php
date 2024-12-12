<?php


namespace DigipolisGent\SettingBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * Class IntegerFieldType
 * @package DigipolisGent\SettingBundle\FieldType
 */
class IntegerFieldType extends AbstractFieldType
{

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'integer';
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return IntegerType::class;
    }

    /**
     * @param $value
     * @return array
     */
    public function getOptions($value): array
    {
        $options = [];
        $options['attr']['min'] = 0;
        $options['attr']['value'] = $value ? $value : '';
        return $options;
    }

    /**
     * @param $value
     * @return bool
     */
    public function decodeValue($value)
    {
        return (integer)$value;
    }

    /**
     * @param $value
     * @return string
     */
    public function encodeValue($value): ?string
    {
        return (string)$value;
    }
}
