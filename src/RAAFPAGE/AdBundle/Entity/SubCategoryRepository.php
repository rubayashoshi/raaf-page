<?php

namespace RAAFPAGE\AdBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SubCategoryRepository extends EntityRepository
{
    public function findAllSubCategories($categoryId)
    {
        if (!is_int($categoryId)) {
            return array();
        }

        $sql = "SELECT t3.id, t2.parent_id as parent2, t3.parent_id as parent3, t1.name AS lev1, t2.name as lev2, " .
            "t3.name as lev3, t4.name as lev4 FROM sub_category AS t1 " .
            "LEFT JOIN sub_category AS t2 ON t2.parent_id = t1.id " .
            "LEFT JOIN sub_category AS t3 ON t3.parent_id = t2.id " .
            "LEFT JOIN sub_category AS t4 ON t4.parent_id = t3.id " .
            "WHERE t1.category_id = {$categoryId} AND t3.parent_id != ''";

        $results = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
        $menu = array();
        $array = array();

        foreach ($results as $subCategory2) {
            foreach ($results as $subCategory3) {
                $array[$subCategory3['lev2']][$subCategory3['lev3']] = $subCategory3['lev3'];
            }
            $menu[$subCategory2['lev1']] = $array;
        }

        return $menu;
    }

} 