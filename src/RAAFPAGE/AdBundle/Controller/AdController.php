<?php

namespace RAAFPAGE\AdBundle\Controller;

use RAAFPAGE\AdBundle\Entity\Property;
use RAAFPAGE\AdBundle\Form\Type\PropertyType;
use RAAFPAGE\AdBundle\Service\AdManager;
use RAAFPAGE\AdBundle\Service\FileUploader;
use RAAFPAGE\UserBundle\Entity\User;
use RAAFPAGE\AdBundle\Service\FileImageInfo;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    public function ajaxUploadAction()
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
        if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

            // check $_FILES['ImageFile'] not empty
            if(!isset($_FILES['image_file']) || !is_uploaded_file($_FILES['image_file']['tmp_name'])){
                die('Image file is Missing!'); // output error when above checks fail.
            }

            //uploaded file info we need to proceed
            $imageName = $_FILES['image_file']['name']; //file name
            $imageSize = $_FILES['image_file']['size']; //file size
            $imageTemp = $_FILES['image_file']['tmp_name']; //file temp

            $imageSizeInfo 	= getimagesize($imageTemp); //get image size

            if($imageSizeInfo){
                $image_width 		= $imageSizeInfo[0]; //image width
                $image_height 		= $imageSizeInfo[1]; //image height
                $image_type 		= $imageSizeInfo['mime']; //image type
            }else{
                die("Make sure image file is valid!");
            }

            //switch statement below checks allowed image type
            //as well as creates new image from given file
            switch($image_type){
                case 'image/png':
                    $image_res =  imagecreatefrompng($imageTemp); break;
                case 'image/gif':
                    $image_res =  imagecreatefromgif($imageTemp); break;
                case 'image/jpeg': case 'image/pjpeg':
                $image_res = imagecreatefromjpeg($imageTemp); break;
                default:
                    $image_res = false;
            }

            if($image_res){
                //Get file extension and name to construct new file name
                $image_info = pathinfo($imageName);
                $image_extension = strtolower($image_info["extension"]); //image extension
                $image_name_only = strtolower($image_info["filename"]);//file name only, no extension

                //create a random name for new image (Eg: fileName_293749.jpg) ;
                //$new_file_name = $image_name_only. '_' .  rand(0, 9999999999) . '.' . $image_extension;
                $new_file_name = $_POST['image_id'] . '.' . $image_extension;

                FileImageInfo::setImageName($user->getId(), $new_file_name);
                $normalImageFolderPath = FileImageInfo::getNormalImageDestinationPath();
                if ($propertyId > 0) {
                    $adManager->addImageToProperty($property, $this->getDoctrine()->getManager(), $fileUploader, $normalImageFolderPath);
                }

                //call normal_resize_image() function to proportionally resize image
                if(
                $fileUploader->normalResizeImage(
                    $image_res,
                    $normalImageFolderPath,
                    $image_type,
                    FileImageInfo::$max_image_size,
                    $image_width,
                    $image_height,
                    FileImageInfo::$jpeg_quality
                    )
                ){
                    //call crop_image_square() function to create square thumbnails
                    if(!$fileUploader->cropImageSquare(
                        $image_res,
                        FileImageInfo::getThumbImageDestinationPath(),
                        $image_type,
                        FileImageInfo::$_thumb_square_size,
                        $image_width,
                        $image_height,
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
                imagedestroy($image_res);
            }
        }

        return array('path' => $filePathTemp.'?id='.time());
    }

    /**
     * @Route("/seller/add/edit/{id}", name="edit_add")
     * @Template()
     */
    public function editAction($id=null)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $temporaryAdId = time().$user->getId();

        if ($id) {
            $property = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:Property')
                ->find($id);
        } else {
            $property = new Property();
        }

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

                foreach ($_POST['add_type'] as $type) {
                    $adType = $this->getDoctrine()->getRepository('RAAFPAGEAdBundle:AdType')
                        ->find($type);
                    $property->addAdType($adType);
                }

                //move images from temp folder and add them into database for new ad
                /** @var FileUploader $fileUploader */
                if (!$property->getId()) {
                    $fileUploader = $this->get('raafpage.adbundle.file_uploader');
                    $fileUploader->moveImageTo($property, $user->getId());
                    //$fileUploader->attacheImageToProperty($property, $user->getId());
                }

                $property->setUser($user);

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($property);
                $manager->flush();

                return $this->redirect($this->generateUrl('ad_list'));
            } else {
                //var_dump($form->getErrorsAsString());die;
                $error = 'Form has some fields incomplete or missing,' .
                    'please check and complete all required information.';
            }
        }

        $images = array(
            0 => 'missing',
            1 => 'missing',
            2 => 'missing',
            3 => 'missing'
        );

        $i = 0;

        foreach ($property->getImages() as $image) {
            if (stripos($image->getAddress(), 'thumb')) {
                unset($images[$i]);
                $images[$i] =  $image->getAddress();
                $i++;
            }
        }

        return array('form' => $form->createView(),'images' => $images, 'temporaryAdId' => $temporaryAdId,'property' => $property, 'types' => $types, 'error' => $error);
    }
}
