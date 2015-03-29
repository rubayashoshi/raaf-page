<?php

namespace RAAFPAGE\UserBundle\Controller;

use RAAFPAGE\UserBundle\Entity\User;
use RAAFPAGE\UserBundle\Form\Type\UserType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RAAFPAGEUserBundle:Admin:index.html.twig');
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


        return $this->render('RAAFPAGEUserBundle:Default:index.html.twig');
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


        return $this->render('RAAFPAGEUserBundle:Default:index.html.twig');
    }
}
