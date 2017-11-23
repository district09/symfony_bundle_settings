<?php


namespace DigipolisGent\SettingBundle\DataFixtures\ORM;


use DigipolisGent\Domainator9k\CoreBundle\Entity\Environment;
use DigipolisGent\SettingBundle\Entity\SettingDataType;
use DigipolisGent\SettingBundle\Entity\SettingEntityType;
use DigipolisGent\SettingBundle\Service\EntityTypeCollector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

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

        foreach ($entityTypeCollector->getEntityTypes() as $entityTypeArr) {
            $name = $entityTypeArr['name'];
            $class = $entityTypeArr['class'];

            $entityType = $manager->getRepository(SettingEntityType::class)->findOneBy(['class' => $class]);

            if (is_null($entityType)) {
                $entityType = new SettingEntityType();
                $entityType->setClass($class);
            }

            $entityType->setName($name);
            $manager->persist($entityType);

            $manager->flush();
        }
    }
}