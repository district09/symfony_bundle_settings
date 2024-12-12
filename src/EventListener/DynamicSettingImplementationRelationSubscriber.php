<?php


namespace DigipolisGent\SettingBundle\EventListener;

use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\Entity\Traits\SettingImplementationTrait;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

/**
 * Class DynamicSettingImplementationRelationSubscriber
 * @package DigipolisGent\SettingBundle\EventListener
 */
#[AsDoctrineListener(event: Events::loadClassMetadata)]
class DynamicSettingImplementationRelationSubscriber
{
    /**
     * @param LoadClassMetadataEventArgs $loadClassMetadataEventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $metadataEventArgs)
    {
        $metadata = $metadataEventArgs->getClassMetadata();
        $rootMetadata = $metadata;
        if (!$metadata->isRootEntity()) {
            $rootMetadata = $metadataEventArgs->getEntityManager()->getClassMetadata($metadata->rootEntityName);
        }
        if (!in_array(SettingImplementationTrait::class, $metadata->getReflectionClass()->getTraitNames())) {
            return;
        }

        $namingStrategy = $metadataEventArgs->getEntityManager()->getConfiguration()->getNamingStrategy();
        $namePrefix = $rootMetadata->getTableName();
        $namePrefix = strtolower(ltrim(preg_replace('/[A-Z]/', '_$0', $namePrefix), '_'));

        $metadata->mapManyToMany(array(
            'targetEntity' => SettingDataValue::class,
            'fieldName' => 'settingDataValues',
            'cascade' => array('all'),
            'orphanRemoval' => true,
            'joinTable' => array(
                'name' => $namePrefix . '_data_value',
                'joinColumns' => array(
                    array(
                        'name' => $namePrefix . '_id',
                        'referencedColumnName' => $namingStrategy->referenceColumnName(),
                        'onDelete' => 'CASCADE',
                        'onUpdate' => 'CASCADE',
                    ),
                ),
                'inverseJoinColumns' => array(
                    array(
                        'name' => 'data_value_id',
                        'referencedColumnName' => $namingStrategy->referenceColumnName(),
                        'onDelete' => 'CASCADE',
                        'onUpdate' => 'CASCADE',
                    ),
                )
            )
        ));
    }
}
