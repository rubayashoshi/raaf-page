<?php

namespace RAAFPAGE\AdBundle\Controller;

use RAAFPAGE\AdBundle\Entity\Property;
use RAAFPAGE\AdBundle\Form\Type\PropertyType;
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
     * @Route("/seller/add/upload", name="upload_add")
     * @Template()
     */
    public function uploadAction()
    {
        $property = new Property();
        $form = $this->createForm(new PropertyType(), $property);
        $form->handleRequest($this->getRequest());

        if ($this->getRequest()->isMethod('POST')) {
            if ($form->isValid()) {
                $property = $form->getData();
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($property);
                $manager->flush();
            }
        }

        return array('form' => $form->createView());
    }
}
