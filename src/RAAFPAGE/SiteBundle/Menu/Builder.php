<?php

namespace RAAFPAGE\SiteBundle\Menu;

use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use RAAFPAGE\AdBundle\Entity\Category;
use RAAFPAGE\SiteBundle\Service\Utils\StringManipulator;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @return ItemInterface
     */
    public function mainMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');

        /** @var EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine')->getManager();
        $categories = $entityManager->getRepository('RAAFPAGEAdBundle:Category')
            ->findAllCategories();

        $subCategoryRepository = $entityManager->getRepository('RAAFPAGEAdBundle:SubCategory');

        $subCategoriesAsFlatArray = $subCategoryRepository->findAllSubCategoriesFlatArray();

        /** @var Category $category */
        foreach ($categories as $category) {
            $property = $factory->createItem(
                $category->getName(),
                array(
                    'route' => 'front_end_ad_category_list',
                    'routeParameters' => array(
                        'category' => StringManipulator::convertToSlug($category->getName())
                    )
                )
            );

            $menuItems = $entityManager->getRepository('RAAFPAGEAdBundle:SubCategory')
                ->findAllSubCategories($category->getId());

            foreach ($menuItems as $key => $menuItem) {
                $toRent = $factory->createItem(
                    $key,
                    array(
                        'route' => 'front_end_ad_sub_category_list',
                        'routeParameters' => array(
                            's' => 's',
                            'path' => $subCategoriesAsFlatArray[StringManipulator::convertToSlug($key)]
                        )
                    )
                );

                foreach ($menuItem as $key2 => $items) {
                    $offered = $factory->createItem(
                        $key2,
                        array(
                            'route' => 'front_end_ad_sub_category_list',
                            'routeParameters' => array(
                                'path' => $subCategoriesAsFlatArray[StringManipulator::convertToSlug($key2)],
                            )
                        )
                    );

                    foreach ($items as $key3 => $item) {
                        $propertySize = $factory->createItem(
                            $key3,
                            array(
                                'route' => 'front_end_ad_sub_category_list_with_size',
                                'routeParameters' => array(
                                    'slug' =>  $subCategoriesAsFlatArray[StringManipulator::convertToSlug($key3)],
                                )
                            )
                        );

                        $offered->addChild($propertySize);
                    }

                    $toRent->addChild($offered);
                }

                $property->addChild($toRent);
            }

            $menu->addChild($property);
        }

        return $menu;
    }
}
