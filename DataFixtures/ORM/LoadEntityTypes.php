<?php


namespace DigipolisGent\SettingBundle\DataFixtures\ORM;

use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\Entity\Traits\SettingImplementationTrait;
use DigipolisGent\SettingBundle\Service\EntityTypeCollector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class LoadEntityTypes
 * @package AppBundle\DataFixtures\ORM
 */
class LoadEntityTypes extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $entityTypeRepository = $manager->getRepository(SettingEntityType::class);
        $entityTypeNames = [];
        $allMetadata = $manager->getMetadataFactory()->getAllMetadata();

        foreach ($allMetadata as $metadata) {
            $className = $metadata->getName();

            if (!in_array(SettingImplementationTrait::class, class_uses($className))) {
                continue;
            }

            $entityTypeName = $className::getSettingImplementationName();
            $entityTypeNames[] = $entityTypeName;

            $entityType = $entityTypeRepository->findOneBy(['name' => $entityTypeName]);

            if (is_null($entityType)) {
                $entityType = new SettingEntityType();
                $entityType->setName($entityTypeName);
                $manager->persist($entityType);
            }
        }

        $manager->flush();

        $entityTypes = $entityTypeRepository->findAll();
        foreach ($entityTypes as $entityType) {
            if (!in_array($entityType->getName(), $entityTypeNames)) {
                $manager->remove($entityType);
            }
        }

        $manager->flush();
    }
}
