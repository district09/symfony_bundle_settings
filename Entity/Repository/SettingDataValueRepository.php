<?php


namespace DigipolisGent\SettingBundle\Entity\Repository;

use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class SettingDataValueRepository extends EntityRepository
{

    /**
     * @param $entity
     * @param $key
     * @return SettingDataValue|null
     */
    public function findOneByKey($entity, $key)
    {
        $em = $this->getEntityManager();
        $classMetadata = $em->getClassMetadata(get_class($entity));
        $rootClassMetadata = $em->getClassMetadata($classMetadata->rootEntityName);

        $namePrefix =  $rootClassMetadata->getTableName();
        $namePrefix = strtolower(ltrim(preg_replace('/[A-Z]/', '_$0', $namePrefix), '_'));

        $sql = "SELECT dv.id,dv.setting_v_value,setting_data_type_id " .
            "FROM setting_data_value dv " .
            "LEFT JOIN setting_data_type dt ON dt.id = dv.setting_data_type_id " .
            "INNER JOIN " . $namePrefix . "_data_value e ON e.data_value_id = dv.id " .
            "WHERE dt.setting_dt_key = ? " .
            "AND e." . $namePrefix . "_id = ? ";

        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata(SettingDataValue::class, 'dv');

        $id = $entity->getId();
        $type = $rootClassMetadata->getTypeOfField('id');
        if (in_array($type, ['ulid', 'uuid', 'guid'])) {
            // Convert values into right type
            if (Type::hasType($type)) {
                $doctrineType = Type::getType($type);
                $platform = $em->getConnection()->getDatabasePlatform();
                $id = $doctrineType->convertToDatabaseValue($id, $platform);
            }
        }

        $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $key);
        $query->setParameter(2, $id);

        return $query->getOneOrNullResult();
    }
}
