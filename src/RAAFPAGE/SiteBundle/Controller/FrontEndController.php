<?php

namespace RAAFPAGE\SiteBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FrontEndController extends Controller
{
    /**
     * @Route("/ads/{category}/{type}", name = "front_end_ad_category_list")
     * @param string $category
     * @param string $type
     * @return Response
     */
    public function categoryAction($category, $type)
    {
        return $this->render('RAAFPAGESiteBundle:Default:home.html.twig');
    }

    /**
     * @Route("/ads/{category}/{type}", name = "front_end_ad_sub_category_list")
     * @param string $category
     * @param string $type
     * @return Response
     */
    public function subCategoryAction($category, $type)
    {
        return $this->render('RAAFPAGESiteBundle:Default:home.html.twig');
    }
}
