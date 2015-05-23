<?php

namespace RAAFPAGE\AdBundle\Controller;

use RAAFPAGE\AdBundle\Entity\Property;
use RAAFPAGE\AdBundle\Form\Type\PropertyType;
use RAAFPAGE\AdBundle\Service\AdManager;
use RAAFPAGE\AdBundle\Service\FileManager;
use RAAFPAGE\AdBundle\Service\FileUploader;
use RAAFPAGE\AdBundle\Service\FileImageInfo;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdController
 * @package RAAFPAGE\AdBundle\Controller
 * @Route("/seller")
 */
class AdController extends Controller
{
    /**
     * @Route("/home")
     * @Template()
     */
    public function homeAction()
    {
        return array();
    }

    /**
     * Display all ads in customer secured page
     *
     * @Route("ad/list/{status}", name = "ad_list")
     * @Template()
     */
    public function listAction($status =  'live')
    {
        $user = $this->get('security.context')->getToken()->getUser();

        /** @var AdManager $adManager */
        $adManager = $this->get('raafpage.adbundle.ad_manager');
        $ads = $adManager->getAllLiveAdsByUser($user, $status);
        $images = $adManager->getDefaultImages($ads);

        return array('ads' => $ads, 'images' => $images, 'status' => $status);
    }

    /**
     * Post an ad page to allow customer select the section the will be in
     *
     * @Route("/postanad", name="post-an-ad")
     */
    public function postAnAdd()
    {
        return $this->render('RAAFPAGEAdBundle:Ad:postanad.html.twig');
    }

    /**
     * Upload the ad
     *
     * @Route("/ad/add", name="add_ad")
     * @Template()
     * @todo move most of the code into a service to slim the controller action
     */
    public function addAction()
    {
        /** @var FileUploader $fileUploader */
        $fileUploader = $this->get('raafpage.adbundle.file_uploader');

        $user = $this->get('security.context')->getToken()->getUser();
        $temporaryAdId = time() . $user->getId();
        $property = new Property();

        $form = $this->createForm(new PropertyType(), $property);

        $types = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:AdType')
            ->findAll();

        $error = null;

        if ($this->getRequest()->isMethod('POST')) {
            $form->handleRequest($this->getRequest());

            if ($form->isValid()) {
                /** @var Property $property */
                $property = $form->getData();

                foreach ($property->getAdTypes() as $adType) {
                    $property->removeAdType($adType);
                }

                if (isset($_POST['add_type']) && count($_POST['add_type'])) {
                    foreach ($_POST['add_type'] as $addTypeSelected) {
                        $adType = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:AdType')
                            ->find($addTypeSelected);
                        $property->addAdType($adType);
                    }
                }

                //move images from temp folder and add them into database for new ad
                /** @var FileUploader $fileUploader */
                if (! $property->getId()) {
                    $fileUploader->moveImageTo($property, $user->getId());
                    //$fileUploader->attacheImageToProperty($property, $user->getId());
                }

                $property->setUser($user);
                $adStatus = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:Status')
                    ->findOneBy(array('name' => 'live'));
                $property->setStatus($adStatus);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($property);
                $manager->flush();

                return $this->redirect($this->generateUrl('ad_list'));
            } else {
                $error = 'Form has some fields incomplete or missing,' .
                    'please check and complete all required information.';
            }
        }

        $images = $fileUploader->getImagesForNewAd($user->getId());

        return array('form' => $form->createView(),'images' => $images, 'temporaryAdId' => $temporaryAdId,'property' => $property, 'types' => $types, 'error' => $error);
    }

    /**
     * Modify the ad
     *
     * @Route("/ad/edit/{id}", name="edit_add")
     * @Template()
     * @todo move most of the code into a service to slim the controller action
     */
    public function editAction(Property $property)
    {
        /** @var FileUploader $fileUploader */
        $fileUploader = $this->get('raafpage.adbundle.file_uploader');
        $user = $this->get('security.context')->getToken()->getUser();
        $temporaryAdId = time() . $user->getId();

        $form = $this->createForm(new PropertyType(), $property);
        $types = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:AdType')
            ->findAll();

        $error = null;

        if ($this->getRequest()->isMethod('POST')) {
            $form->handleRequest($this->getRequest());

            if ($form->isValid()) {
                /** @var Property $property */
                $property = $form->getData();

                foreach ($property->getAdTypes() as $adType) {
                    $property->removeAdType($adType);
                }

                if (isset($_POST['add_type']) && count($_POST['add_type'])) {
                    foreach ($_POST['add_type'] as $addTypeSelected) {
                        $adType = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:AdType')
                            ->find($addTypeSelected);
                        $property->addAdType($adType);
                    }
                }

                $property->setUser($user);
                $adStatus = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:Status')
                    ->findOneBy(array('name' => 'live'));
                $property->setStatus($adStatus);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($property);
                $manager->flush();

                return $this->redirect($this->generateUrl('ad_list'));
            } else {
                $error = 'Form has some fields incomplete or missing,' .
                    'please check and complete all required information.';
            }
        }

        $images = $fileUploader->getImages($property);

        return array('form' => $form->createView(),'images' => $images, 'temporaryAdId' => $temporaryAdId,'property' => $property, 'types' => $types, 'error' => $error);
    }

    /**
     * Archive an ad on customer request
     *
     * @Route("/ad/delete/{id}", name = "ad_delete")
     */
    public function deleteAction(Property $property)
    {
        /** @var AdManager $adManager */
        $adManager = $this->get('raafpage.adbundle.ad_manager');
        $adManager->delete($property);

        return $this->redirect($this->generateUrl('ad_list'));
    }

    /**
     * Delete an image from the property
     *
     * @Route("add/delete-image/{imageId}")
     */
    public function removeAction($imageId = null)
    {
        /** @var AdManager $addManager */
        $propertyId = (int) $this->getRequest()->get('property_id');

        $adManager = $this->get('raafpage.ad_bundle.ad_manager');
        $adManager->removeImageFromExistingProperty($imageId, $propertyId);

        return new JsonResponse(array('status' => 'OK'));
    }

    /**
     * Upload an image using ajax request
     *
     * @Route("/add/ajax-upload", name="upload_image")
     * @Template()
     * @todo move most of the code into a service to slim the controller action
     */
    public function ajaxUploadAction(Request $request)
    {
        if (isset($_POST) && $request->isXmlHttpRequest()) {
            /** @var FileManager $fileManager */
            $fileManager = $this->get('raafpage.ad_bundle.file_manager');
            $propertyId = $this->getRequest()->get('property_id');

            if ($propertyId > 0) {
                FileImageInfo::setPropertyId($propertyId);
            }

            $filePathTemp = $fileManager->uploadAnImageAndReturnWebPath();

            if ($propertyId > 0) {
                /** @var AdManager $adManager */
                $adManager = $this->get('raafpage.ad_bundle.ad_manager');

                //add normal size image into property
                $normalImageFolderPath = FileImageInfo::getNormalImageDestinationPath();
                $adManager->addImageToProperty($propertyId, $normalImageFolderPath);

                //add thumb image into property
                $thumbImageFolderPath = FileImageInfo::getThumbImageDestinationPath();
                $adManager->addImageToProperty($propertyId, $thumbImageFolderPath);
            }
        }

        return array('path' => $filePathTemp.'?id='.time());
    }
}
