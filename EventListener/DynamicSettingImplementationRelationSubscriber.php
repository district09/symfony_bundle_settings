<?php


namespace DigipolisGent\SettingBundle\EventListener;

use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use DigipolisGent\SettingBundle\Entity\Traits\SettingImplementationTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

/**
 * Class DynamicSettingImplementationRelationSubscriber
 * @package DigipolisGent\SettingBundle\EventListener
 */
class DynamicSettingImplementationRelationSubscriber implements EventSubscriber
{

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::loadClassMetadata,
        );
    }

    /**
     * @param LoadClassMetadataEventArgs $loadClassMetadataEventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $loadClassMetadataEventArgs)
    {
        $namingStrategy = $loadClassMetadataEventArgs->getEntityManager()->getConfiguration()->getNamingStrategy();
        $metadata = $loadClassMetadataEventArgs->getClassMetadata();

        if (!in_array(SettingImplementationTrait::class, class_uses($metadata->getName()))) {
            return;
        }

        $metadata->mapManyToMany(array(
            'targetEntity' => SettingDataValue::class,
            'fieldName' => 'settingDataValues',
            'cascade' => array('all'),
            'orphanRemoval' => true,
            'joinTable' => array(
                'name' => strtolower($namingStrategy->classToTableName($metadata->getName())) . '_data_value',
                'joinColumns' => array(
                    array(
                        'name' => $namingStrategy->joinKeyColumnName($metadata->getName()),
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
