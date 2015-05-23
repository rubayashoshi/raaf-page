<?php

namespace RAAFPAGE\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use RAAFPAGE\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class UserManager
{

    /** @var EntityManager */
    private $entityManager;

    /** @var MailSender */
    private $mailSender;

    /**
     * @var EncoderFactory
     */
    private $encoderFactory;

    /**
     * @param EntityManager $entityManager
     * @param EncoderFactory $encoderFactory
     * @param MailSender $mailSender
     */
    public function __construct(
        EntityManager $entityManager,
        EncoderFactory $encoderFactory,
        MailSender $mailSender
    ) {
        $this->entityManager = $entityManager;
        $this->mailSender = $mailSender;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param User $user
     */
    public function handleUserRegistration($user)
    {
        $password = $user->getPassword();

        // encode the password
        $encoder = $this->encoderFactory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
        $user->setPassword($encodedPassword);
        $user->setRole('ROLE_USER');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->mailSender->sendMail($user);
    }

    /**
     * @param integer $id
     */
    public function activateAccount($id)
    {
        $userId = UserDataEncoder::decode($id);

        /** @var User $user */
        $user = $this->entityManager->getRepository('RAAFPAGEUserBundle:User')->find($userId);
        $user->setActive(true);
        $user->setActivationTime(new \DateTime('NOW'));

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }
}
