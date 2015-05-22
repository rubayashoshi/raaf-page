<?php

namespace RAAFPAGE\UserBundle\Service;

use RAAFPAGE\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\Container;

class MailSender
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container, Router $router)
    {
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * @param User $user
     */
    public function sendMail(User $user)
    {
        $encodedUserId = UserDataEncoder::encode($user->getId());
        $url = $this->router->generate(
            'activate_user_account',
            array('id' => $encodedUserId),
            true
        );

        $content = $this->container->get('templating')->renderResponse(
            'RAAFPAGEUserBundle:Email:registration.confirmation.html.twig',
            array(
                'url' => $url,
                'user' => $user
            )
        );

        $message = \Swift_Message::newInstance()
            ->setSubject('User registration confirmation')
            ->setTo($user->getEmail())
            ->setFrom('rubayashoshi@gmail.com')
            ->setBody($content, 'text/html');
        $mailer = $this->container->get('mailer');
        $mailer->send($message);
    }
}
