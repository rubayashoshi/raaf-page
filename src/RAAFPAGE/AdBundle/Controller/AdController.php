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
    public function uploadAction($id = null)
    {
        if ($id) {
            $property = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:Property')
                ->find($id);
        } else {
            $property = new Property();
        }

        $form = $this->createForm(new PropertyType(), $property);
        $form->handleRequest($this->getRequest());

        $types = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:AdType')
            ->findAll();
        $error = 's';
        if ($this->getRequest()->isMethod('POST')) {
            if ($form->isValid()) {
                $property = $form->getData();
                foreach ($_POST['add_type'] as $type) {
                    $adType = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:AdType')
                        ->find($type);
                    $property->addAdType($adType);
                }

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($property);
                $manager->flush();
            } else {
                $error = 'Form has some fields incomplete or missing,' .
                    'please check and complete all required information.';
            }
        }

        return array('form' => $form->createView(), 'types' => $types, 'error' => $error);
    }

    /**
     * @Route("/seller/add/edit/{id}", name="edit_add")
     * @Template()
     */
    public function editAction($id=null)
    {
        if ($id) {
            $property = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:Property')
                ->find($id);
        } else {
            $property = new Property();
        }

        $form = $this->createForm(new PropertyType(), $property);
        $form->handleRequest($this->getRequest());

        $types = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:AdType')
            ->findAll();
        $error = '';
        if ($this->getRequest()->isMethod('POST')) {
            if ($form->isValid()) {
                /** @var Property $property */
                $property = $form->getData();
                foreach ($property->getAdTypes() as $adType) {
                    $property->removeAdType($adType);
                }

                foreach ($_POST['add_type'] as $type) {
                    $adType = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:AdType')
                        ->find($type);
                    $property->addAdType($adType);
                }

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($property);
                $manager->flush();
            } else {
                $error = 'Form has some fields incomplete or missing,' .
                    'please check and complete all required information.';
            }
        }

        return array('form' => $form->createView(), 'property' => $property, 'types' => $types, 'error' => $error);
    }
}
