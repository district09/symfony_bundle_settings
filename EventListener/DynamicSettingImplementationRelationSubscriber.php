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
    public function loadClassMetadata(LoadClassMetadataEventArgs $metadataEventArgs)
    {
        $namingStrategy = $metadataEventArgs->getEntityManager()->getConfiguration()->getNamingStrategy();
        $metadata = $metadataEventArgs->getClassMetadata();

        if (!in_array(SettingImplementationTrait::class, class_uses($metadata->getName()))) {
            return;
        }

        $joinTableName = strtolower(ltrim(preg_replace('/[A-Z]/', '_$0', $namingStrategy->classToTableName($metadata->getName())), '_')).'_data_value';
        $joinColumnName = strtolower(ltrim(preg_replace('/[A-Z]/', '_$0', $namingStrategy->classToTableName($metadata->getName())), '_')).'_id';

        $metadata->mapManyToMany(array(
            'targetEntity' => SettingDataValue::class,
            'fieldName' => 'settingDataValues',
            'cascade' => array('all'),
            'orphanRemoval' => true,
            'joinTable' => array(
                'name' => $joinTableName,
                'joinColumns' => array(
                    array(
                        'name' => $joinColumnName,
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
