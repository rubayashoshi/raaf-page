<?php

namespace RAAFPAGE\AdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdController extends Controller
{
    /**
     * @Route("/seller/add/list")
     * @Template()
     */
    public function listAction()
    {
        return array();
    }

    /**
     * @Route("/seller/add/upload")
     * @Template()
     */
    public function uploadAction()
    {
        return array();
    }
}
