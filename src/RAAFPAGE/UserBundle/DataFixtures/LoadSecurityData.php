<?php

namespace RAAFPAGE\UserBundle\DataFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use RAAFPAGE\UserBundle\Entity\User;
use RAAFPAGE\UserBundle\Entity\Role;

/**
 * Load test users.
 * @author James Morris <james@jmoz.co.uk>
 */
class LoadSecurityData implements FixtureInterface
{
    /**
     * Take the password encoder factory
     * @param type $factory
     */
    public function __construct( $factory )
    {
        $this->factory = $factory;
    }

    public function load( $manager )
    {
        $this->loadUsers( $manager );
        $this->loadRoles( $manager );
        $this->loadUsersRoles( $manager );
    }

    public function truncate( $manager )
    {
        $manager->getConnection()->exec('SET FOREIGN_KEY_CHECKS=0');
        $sql = $manager->getConnection()->getDatabasePlatform()->getTruncateTableSQL( 'users_roles' );

        $manager->getConnection()->executeUpdate( $sql );
        $sql = $manager->getConnection()->getDatabasePlatform()->getTruncateTableSQL( 'users' );

        $manager->getConnection()->executeUpdate( $sql );
        $sql = $manager->getConnection()->getDatabasePlatform()->getTruncateTableSQL( 'roles' );

        $manager->getConnection()->executeUpdate( $sql );
        $manager->getConnection()->exec('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Use the password encoder to set the $password on the $user
     * @param User $user
     * @param string $password
     */
    private function setEncodedPassword( User $user, $password )
    {
        $encoder = $this->factory->getEncoder( $user );
        $password = $encoder->encodePassword( $password, $user->getSalt() );
        $user->setPassword( $password );
    }

    public function loadUsers( $manager )
    {
        $user = new User();
        $user->setUsername( 'user' );
        //$u->setAlgorithm( 'sha512' );
        $this->setEncodedPassword( $user, 'password123' );
        $user->setActive( true );
        $user->setEmail( 'testuser1@test.com' );

        $manager->persist( $user );
        $manager->flush();

        $user = new User();
        $user->setUsername( 'admin' );
        //$user->setAlgorithm( 'sha512' );
        $this->setEncodedPassword( $user, 'password123' );
        $user->setActive( true );
        $user->setEmail( 'testuser2@test.com' );

        $manager->persist( $user );
        $manager->flush();
    }

    public function loadRoles( $manager )
    {
        $role = new Role( 'ROLE_USER' );
        $manager->persist( $role );

        $role = new Role( 'ROLE_ADMIN' );
        $manager->persist( $role );

        $manager->flush();
    }

    /**
     * Using raw sql as the SecurityTest needs to test functionality of addRole() etc so can not use in here.
     */
    public function loadUsersRoles( $manager )
    {
        $manager->getConnection()->exec( "insert into users_roles values (1, 1), (2,2)" );
        $manager->clear();
    }
}
