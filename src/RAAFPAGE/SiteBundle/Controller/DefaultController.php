<?php

namespace RAAFPAGE\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function homeAction()
    {
        return $this->render('RAAFPAGESiteBundle:Default:home.html.twig');
    }
}
