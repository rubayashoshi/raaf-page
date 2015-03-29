<?php

namespace HRPROJECT\UserBundle\Controller;

use HRPROJECT\UserBundle\Entity\User;
use HRPROJECT\UserBundle\Form\Type\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('HRPROJECTUserBundle:Admin:index.html.twig');
    }

    public function homeAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $securityContext = $this->get('security.context');

        if ($securityContext->isGranted('ROLE_ADMIN')){
            echo 'I am admin';
        }
        if ($securityContext->isGranted('ROLE_USER')){
            echo 'I am seller';
        }


        return $this->render('HRPROJECTUserBundle:Default:index.html.twig');
    }

    public function sellerAction()
    {
        $securityContext = $this->get('security.context');

        if ($securityContext->isGranted('ROLE_ADMIN')){
            echo 'I am admin';
        }
        if ($securityContext->isGranted('ROLE_USER')){
            echo 'I am seller';
        }


        return $this->render('HRPROJECTUserBundle:Default:index.html.twig');
    }
}
