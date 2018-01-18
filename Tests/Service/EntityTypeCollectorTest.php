<?php


namespace DigipolisGent\SettingBundle\Tests\Service;


use DigipolisGent\SettingBundle\Service\EntityTypeCollector;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Bar;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\Foo;
use DigipolisGent\SettingBundle\Tests\Fixtures\Entity\FooParent;
use DigipolisGent\SettingBundle\Tests\Fixtures\Provider\EntityTypeProvider;
use PHPUnit\Framework\TestCase;

class EntityTypeCollectorTest extends TestCase
{

    public function testAddEntityTypes()
    {
        $entityTypeCollector = new EntityTypeCollector();
        $entityTypeCollector->addEntityTypes(new EntityTypeProvider(['foo' => Foo::class]));
        $entityTypeCollector->addEntityTypes(new EntityTypeProvider(['bar' => Bar::class]));
        $entityTypes = $entityTypeCollector->getEntityTypes();
        $this->assertCount(2, $entityTypes);

        return $entityTypeCollector;
    }

    /**
     * @depends testAddEntityTypes
     */
    public function testGetEntityTypeByClass(EntityTypeCollector $entityTypeCollector)
    {
        $entityTypeName = $entityTypeCollector->getEntityTypeByClass(Foo::class);
        $this->assertEquals('foo', $entityTypeName);
    }

    /**
     * @depends testAddEntityTypes
     */
    public function testGetEntityTypeByParentClass(EntityTypeCollector $entityTypeCollector)
    {
        $entityTypeName = $entityTypeCollector->getEntityTypeByClass(FooParent::class);
        $this->assertEquals('foo', $entityTypeName);
    }

}