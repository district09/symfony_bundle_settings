<?php


namespace DigipolisGent\SettingBundle\Tests\Service;


use DigipolisGent\SettingBundle\Service\DataTypeCollector;
use DigipolisGent\SettingBundle\Tests\Fixtures\Provider\DataTypeProvider;
use PHPUnit\Framework\TestCase;

class DataTypeCollectorTest extends TestCase
{
    public function testAddDataTypes()
    {
        $dataTypes = [
            [
                'key' => 'foo_bar',
                'label' => 'My label',
                'required' => true,
                'field_type' => 'my_field_type',
                'entity_types' => ['foo'],
            ]
        ];

        $dataTypeCollector = new DataTypeCollector();
        $dataTypeCollector->addDataTypes(new DataTypeProvider($dataTypes));
        $dataTypes = $dataTypeCollector->getDataTypes();
        $this->assertCount(1, $dataTypes);
    }

    /**
     * @expectedException \DigipolisGent\SettingBundle\Exception\KeyNotFoundException
     */
    public function testKeyNotFoundException()
    {
        $dataTypes = [
            [
                'random_key' => 'random_value',
            ]
        ];

        $dataTypeCollector = new DataTypeCollector();
        $dataTypeCollector->addDataTypes(new DataTypeProvider($dataTypes));
    }

}