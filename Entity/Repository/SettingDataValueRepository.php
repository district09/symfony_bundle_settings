<?php


namespace DigipolisGent\SettingBundle\Entity\Repository;

use DigipolisGent\SettingBundle\Entity\SettingDataValue;
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
        $class = get_class($entity);

        $parentClass = get_parent_class($entity);
        if ($parentClass) {
            $class = $parentClass;
        }

        $namingStrategy = $this->_em->getConfiguration()->getNamingStrategy();
        $namePrefix = $namingStrategy->classToTableName($class);
        $namePrefix = strtolower(ltrim(preg_replace('/[A-Z]/', '_$0', $namePrefix), '_'));

        $sql = "SELECT dv.id,dv.setting_v_value,setting_data_type_id " .
            "FROM setting_data_value dv " .
            "LEFT JOIN setting_data_type dt ON dt.id = dv.setting_data_type_id " .
            "INNER JOIN " . $namePrefix . "_data_value e ON e.data_value_id = dv.id " .
            "WHERE dt.setting_dt_key = ? " .
            "AND e." . $namePrefix . "_id = ? ";

        $rsm = new ResultSetMappingBuilder($this->_em);
        $rsm->addRootEntityFromClassMetadata(SettingDataValue::class, 'dv');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $key);
        $query->setParameter(2, $entity->getId());

        return $query->getOneOrNullResult();
    }
}
