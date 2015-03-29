<?php
// src/HRPROJECT/UserBundle/DataFixtures/ORM/LoadRoleData.php
namespace HRPROJECT\UserBundle\DataFixtures\ORM;

use HRPROJECT\UserBundle\Entity\Role;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadRoleData
 * @package HRPROJECT\UserBundle\DataFixtures\ORM
 */
class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $roleUser = new Role('ROLE_USER');
        $manager->persist($roleUser);
        $manager->flush();

        $roleAdmin = new Role('ROLE_ADMIN');
        $manager->persist($roleAdmin);
        $manager->flush();

        $this->addReference('general_user', $roleUser);
        $this->addReference('admin_user', $roleAdmin);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
