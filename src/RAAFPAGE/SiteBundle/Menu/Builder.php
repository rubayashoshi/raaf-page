<?php

namespace RAAFPAGE\SiteBundle\Menu;

use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
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
        $categories = $entityManager->getRepository('RAAFPAGEAdBundle:Category')->findAllCategories();

        foreach ($categories as $category) {
            $property = $factory->createItem($category->getName(), array('route' => 'front_end_ad_category_list', 'routeParameters' => array('category' => 'property', 'type' => $category->getId())));

            $menuItems = $entityManager->getRepository('RAAFPAGEAdBundle:SubCategory')->findAllSubCategories($category->getId());

            foreach ($menuItems as $key => $menuItem) {
                $toRent = $factory->createItem($key,
                    array('route' => 'front_end_ad_sub_category_list', 'routeParameters' => array('category' => 'property', 'type' => $key))
                );

                foreach ($menuItem as $key2 => $items) {
                    $offered = $factory->createItem($key2,
                        array('route' => 'front_end_ad_sub_category_list', 'routeParameters' => array('category' => 'property', 'type' => $key2))
                    );

                    foreach ($items as $key3 => $item) {
                        $propertySize = $factory->createItem($key3,
                            array('route' => 'front_end_ad_sub_category_list', 'routeParameters' => array('category' => 'property', 'type' => $key3))
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
