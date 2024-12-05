<?php

namespace DigipolisGent\SettingBundle\FieldType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class FileLocationFieldType
 * @package DigipolisGent\SettingBundle\FieldType
 */
class FileLocationFieldType extends AbstractFieldType
{

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

        $options['constraints'] = [
            new Callback(function ($value, ExecutionContextInterface $context) {
                if (!file_exists($value)) {
                    $context->addViolation(
                        sprintf(
                            'The file %s could not be found',
                            $value
                        )
                    );
                }
            })
        ];

        return $options;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'file_location';
    }
}
