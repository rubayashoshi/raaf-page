<?php
namespace HRPROJECT\UserBundle\DataFixtures\ORM;

use HRPROJECT\UserBundle\Entity\User;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $password = 'password123';
        $email = 'user@yahoo.com';
        $user = new User();
        $user->setUsername('user');
        $user->setActive(true);
        $user->setEmail($email);

        /** @var ROLE $role */
        $role = $this->getReference('general_user');
        $user->addRole($role);
        $this->encodePassword($user, $password);
        $manager->persist($user);
        $manager->flush();

        $password = 'password123';
        $email = 'admin@yahoo.com';
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setActive(true);
        $userAdmin->setEmail($email);
        $roleAdmin = $this->getReference('admin_user');
        $userAdmin->addRole($roleAdmin);
        $this->encodePassword($userAdmin, $password);
        $manager->persist($userAdmin);
        $manager->flush();
    }

    /**
     * @param User $user
     * @param string $password
     */
    private function encodePassword(User $user, $password)
    {
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($encodedPassword);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
