<?php

namespace RAAFPAGE\SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name = "raafpage_site_homepage")
     * @return Response
     */
    public function homeAction()
    {
        return $this->render('RAAFPAGESiteBundle:Default:home.html.twig');
    }
}
