<?php

namespace RAAFPAGE\SiteBundle\Controller;

use RAAFPAGE\AdBundle\Service\AdManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class FrontEndController extends Controller
{
    /**
     * Home page of a particular category
     * @Route("/ads/{category}", name = "front_end_ad_category_list")
     * @param string $category
     * @return Response
     */
    public function categoryAction($category)
    {
        return $this->render('RAAFPAGESiteBundle:Default:home.html.twig');
    }

    /**
     * Sub category page
     * @Route("/ads/s/{path}", name = "front_end_ad_sub_category_list")
     * @Template()
     * @param string $path
     * @return Response
     */
    public function subCategoryAction($path = null)
    {
        /** @var AdManager $adManager */
        $adManager = $this->get('raafpage.adbundle.ad_manager');
        $ads = $adManager->getAllLiveAdsBySubCategoryId($path);

        return array('ads' => $ads);
    }

    /**
     * Sub category page
     * @Route("/ads/s/{slug}", name = "front_end_ad_sub_category_list_with_size")
     * @param $slug
     * @return Response
     */
    public function subCategorywithSizeAction($slug)
    {
        die('display all ads for this size');
        return $this->render('RAAFPAGESiteBundle:Default:home.html.twig');
    }
}
