<?php

namespace RAAFPAGE\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * Company admin home page
     * @Route("/admin", name = "admin_home_page")
     * @return Response
     */
    public function homeAction()
    {
        return $this->render('RAAFPAGEUserBundle:Admin:admin.html.twig');
    }
}
