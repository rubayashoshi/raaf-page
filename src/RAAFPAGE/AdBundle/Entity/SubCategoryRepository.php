<?php

namespace RAAFPAGE\AdBundle\Entity;

use Doctrine\ORM\EntityRepository;
use RAAFPAGE\SiteBundle\Service\Utils\StringManipulator;

class SubCategoryRepository extends EntityRepository
{
    public function findAllSubCategories($categoryId)
    {
        if (!is_int($categoryId)) {
            return array();
        }

        $sql = "SELECT t3.id, t2.parent_id as parent2, t3.parent_id as parent3, t1.name AS lev1, t2.name as lev2, " .
            "t3.name as lev3, t3.slug as slug, t4.name as lev4 FROM sub_category AS t1 " .
            "LEFT JOIN sub_category AS t2 ON t2.parent_id = t1.id " .
            "LEFT JOIN sub_category AS t3 ON t3.parent_id = t2.id " .
            "LEFT JOIN sub_category AS t4 ON t4.parent_id = t3.id " .
            "WHERE t1.category_id = {$categoryId} AND t3.parent_id != ''";

        $results = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
        $menu = array();
        $array = array();

        foreach ($results as $subCategory2) {
            foreach ($results as $subCategory3) {
                $array[$subCategory3['lev2']][$subCategory3['lev3']] = $subCategory3['slug'];
            }
            $menu[$subCategory2['lev1']] = $array;
        }

        return $menu;
    }

    /**
     * @return array
     */
    public function findAllSubCategoriesFlatArray()
    {
        $sql = "SELECT `name`, `slug` FROM sub_category ORDER BY id ASC";

        $results = $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
        $menu = array();

        foreach ($results as $subCategory) {
            $menu[StringManipulator::convertToSlug($subCategory['name'])] = $subCategory['slug'];
        }

        return $menu;
    }

    /**
     * @return array
     */
    public function getAllSubCategories()
    {
        $result = $this->getEntityManager()
            ->createQuery("SELECT sc FROM RAAFPAGEAdBundle:SubCategory sc ORDER BY sc.id ASC")
            ->getResult();

        return $result;
    }


    public function getById($id)
    {
        return $this->getEntityManager()->getRepository("RAAFPAGEAdBundle:SubCategory")->find($id);
    }
} 