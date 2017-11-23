<?php


namespace DigipolisGent\SettingBundle\Entity\Repository;


use DigipolisGent\SettingBundle\Entity\SettingDataValue;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class SettingDataValueRepository extends EntityRepository
{

    public function findOneByKey($entity, $key)
    {

        $namingStrategy = $this->_em->getConfiguration()->getNamingStrategy();
        $column = strtolower($namingStrategy->classToTableName(get_class($entity)));

        $sql = "SELECT dv.id,dv.setting_v_value " .
            "FROM setting_data_value dv " .
            "LEFT JOIN setting_data_type dt ON dt.id = dv.setting_data_type_id " .
            "INNER JOIN " . $column . "_data_value e ON e.data_value_id = dv.id " .
            "WHERE dt.setting_dt_key = ? " .
            "AND e." . $column . "_id = ? ";

        $rsm = new ResultSetMappingBuilder($this->_em);
        $rsm->addRootEntityFromClassMetadata(SettingDataValue::class, 'dv');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $key);
        $query->setParameter(2, $entity->getId());

        return $query->getOneOrNullResult();
    }

}