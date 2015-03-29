<?php
namespace RAAFPAGE\UserBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use RAAFPAGE\UserBundle\Entity\User;
use RAAFPAGE\UserBundle\DataFixtures\LoadSecurityData;

/**
 * Class SecurityTest
 * @package RAAFPAGE\UserBundle\Tests\Functional
 */
class SecurityTest extends WebTestCase
{

    private $_em;
    private $_container;

    /**
     * Remember when testing User's they have a default role ROLE_USER which will show +1 in counts
     */
    protected function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->_container = $kernel->getContainer();
        $this->_em = $this->_container->get('doctrine')->getManager('user');
    }

    private function loadData()
    {
        $data = new LoadSecurityData($this->_container->get('security.encoder_factory'));
        $data->truncate($this->_em);
        $data->load($this->_em);
    }

    private function getRole($role)
    {
        return $this->_em->getRepository('JMOZSecurityBundle:Role')->findOneBy(array('role' => $role));
    }

    public function testNewUserHasRoleDefaultRole()
    {
        $user = new User();
        $this->assertTrue($user->hasRole('ROLE_USER'));
        $this->assertTrue($user->hasRole('ROLE_USER')); // call again in case of the addition of duplicate objects (was a bug)
        $this->assertEquals(1, count($user->getRoles()));
    }
}