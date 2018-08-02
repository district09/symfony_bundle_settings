<?php


namespace DigipolisGent\SettingBundle\Tests\Fixtures\Provider;


use DigipolisGent\SettingBundle\Provider\DataTypeProviderInterface;

class DataTypeProvider implements DataTypeProviderInterface
{
    private $dataTypes;

    public function __construct(array $dataTypes)
    {
        $this->dataTypes = $dataTypes;
    }

    public function getDataTypes()
    {
        return $this->dataTypes;
    }
}