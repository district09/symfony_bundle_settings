<?php


namespace DigipolisGent\SettingBundle\Tests\Fixtures\Provider;


use DigipolisGent\SettingBundle\Provider\EntityTypeProviderInterface;

class EntityTypeProvider implements EntityTypeProviderInterface
{
    private $entityTypes;

    public function __construct(array $entityTypes)
    {
        $this->entityTypes = $entityTypes;
    }

    public function getEntityTypes()
    {
        return $this->entityTypes;
    }
}