<?php

namespace RAAFPAGE\AdBundle\Controller;

use RAAFPAGE\AdBundle\Entity\Property;
use RAAFPAGE\AdBundle\Form\Type\PropertyType;
use RAAFPAGE\AdBundle\Service\AdManager;
use RAAFPAGE\AdBundle\Service\FileUploader;
use RAAFPAGE\AdBundle\Service\UploadedFileInfo;
use RAAFPAGE\UserBundle\Entity\User;
use RAAFPAGE\AdBundle\Service\FileImageInfo;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdController extends Controller
{
    /**
     * @Route("/seller/add/list", name = "ad_list")
     * @Template()
     */
    public function listAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        /** @var AdManager $adManager */
        $adManager = $this->get('raafpage.adbundle.ad_manager');
        $ads = $adManager->getAllAdsByUser($user, $this->getDoctrine()->getManager());

        return array('ads' => $ads);
    }

    /**
     * @Route("seller/add/delete-image/{imageId}")
    */
    public function removeAction($imageId = null)
    {
        /** @var FileUploader $fileUploader */
        $fileUploader = $this->get('raafpage.adbundle.file_uploader');
        $property = $this->getRequest()->get('property_id');

        //todo -- merge two into one with just folder path change
        if ($property > 0) {
            $fileUploader->removeImageForExistingProperty($imageId);
        } else {
            $fileUploader->removeImage($imageId);
        }

        return new JsonResponse(array('status' => 'OK'));
    }

    /**
     * @Route("/seller/add/ajax-upload", name="upload_image")
     * @Template()
     */
    public function ajaxUploadAction(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.context')->getToken()->getUser();

        /** @var FileUploader $fileUploader*/
        $fileUploader = $this->get('raafpage.adbundle.file_uploader');
        $propertyId = $this->getRequest()->get('property_id');

        /** @var AdManager $adManager */
        $adManager = $this->get('raafpage.adbundle.ad_manager');
        $property = false;

        if ($propertyId > 0) {
            $property = $adManager->getPropertyById($propertyId, $this->getDoctrine()->getManager());
            FileImageInfo::setPropertyId($propertyId);
        }


        //continue only if $_POST is set and it is a Ajax request
        if (isset($_POST) && $request->isXmlHttpRequest()) {
            //store image information into UploadFileInfo class
            UploadedFileInfo::populateImageInfo();

            switch (UploadedFileInfo::$imageType) {
                case 'image/png':
                    $imageRes =  imagecreatefrompng(UploadedFileInfo::$imageTmpName);
                    break;
                case 'image/gif':
                    $imageRes =  imagecreatefromgif(UploadedFileInfo::$imageTmpName);
                    break;
                case 'image/jpeg':
                case 'image/pjpeg':
                    $imageRes = imagecreatefromjpeg(UploadedFileInfo::$imageTmpName);
                    break;
                default:
                    $imageRes = false;
            }

            if ($imageRes) {
                if ($propertyId > 0) {
                    $newFileName = $_POST['image_id'] . '.' . UploadedFileInfo::$imageExtension;
                } else {
                    $newFileName = $_POST['image_id'] . '.' . UploadedFileInfo::$imageExtension;
                }

                $arr = explode('/', $newFileName);
                $lastPart = $arr[count($arr) - 1];
                FileImageInfo::setImageName($user->getId(), $lastPart);
                $normalImageFolderPath = FileImageInfo::getNormalImageDestinationPath();

                if ($propertyId > 0) {
                    $adManager->addImageToProperty($property, $this->getDoctrine()->getManager(), $fileUploader, $normalImageFolderPath);
                }

                //call normal_resize_image() function to proportionally resize image
                if(
                $fileUploader->normalResizeImage(
                    $imageRes,
                    $normalImageFolderPath,
                    UploadedFileInfo::$imageType,
                    FileImageInfo::$max_image_size,
                    UploadedFileInfo::$imageWidth,
                    UploadedFileInfo::$imageHeight,
                    FileImageInfo::$jpeg_quality
                    )
                ) {
                    //call crop_image_square() function to create square thumbnails
                    if (!$fileUploader->cropImageSquare(
                        $imageRes,
                        FileImageInfo::getThumbImageDestinationPath(),
                        UploadedFileInfo::$imageType,
                        FileImageInfo::$_thumb_square_size,
                        UploadedFileInfo::$imageWidth,
                        UploadedFileInfo::$imageHeight,
                        FileImageInfo::$jpeg_quality)
                    ) {
                        die('Error Creating thumbnail');
                    }

                    //$filePathTemp = FileImageInfo::getImageFullName($user->getId(), $new_file_name);
                    $filePathTemp = FileImageInfo::getImageFullName();

                    if ($propertyId > 0) {
                        $adManager->addImageToProperty($property, $this->getDoctrine()->getManager(), $fileUploader, $filePathTemp);
                    }
                }

                //freeup memory
                imagedestroy($imageRes);
            }
        }

        return array('path' => $filePathTemp.'?id='.time());
    }

    /**
     * @Route("/seller/ad/edit/{id}", name="edit_add")
     * @Template()
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
     * @Route("/seller/ad/add", name="add_ad")
     * @Template()
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
}
