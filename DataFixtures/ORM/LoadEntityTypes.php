<?php


namespace DigipolisGent\SettingBundle\DataFixtures\ORM;


use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\Service\EntityTypeCollector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadEntityTypes
 * @package AppBundle\DataFixtures\ORM
 */
class LoadEntityTypes extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $entityTypeCollector = $this->container->get(EntityTypeCollector::class);
        $entityTypeNames = [];

        foreach ($entityTypeCollector->getEntityTypes() as $name => $class) {
            $entityTypeNames[] = $name;

            $entityType = $manager->getRepository(SettingEntityType::class)->findOneBy(['name' => $name]);

            if (is_null($entityType)) {
                $entityType = new SettingEntityType();
                $entityType->setName($name);
                $manager->persist($entityType);
            }
        }

        $entityTypes = $manager->getRepository(SettingEntityType::class)->findAll();
        foreach ($entityTypes as $entityType) {
            if (!in_array($entityType->getName(), $entityTypeNames)) {
                $manager->remove($entityType);
            }
        }

        $manager->flush();
    }
}