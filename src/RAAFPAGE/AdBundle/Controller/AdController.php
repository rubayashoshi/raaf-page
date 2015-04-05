<?php

namespace RAAFPAGE\AdBundle\Controller;

use RAAFPAGE\AdBundle\Entity\Property;
use RAAFPAGE\AdBundle\Form\Type\PropertyType;
use RAAFPAGE\AdBundle\Service\FileUploader;
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
     * @Route("/seller/add/ajax-upload", name="upload_image")
     * @Template()
     */
    public function ajaxUploadAction()
    {
        /** @var FileUploader $fileUploader*/
        $fileUploader = $this->get('raafpage.adbundle.file_uploader');
        ############ Configuration ##############
                $thumb_square_size 		= 50; //Thumbnails will be cropped to 200x200 pixels
                $max_image_size 		= 50; //Maximum image size (height and width)
                $thumb_prefix			= "thumb_"; //Normal thumb Prefix
                $destination_folder		= '/home/foodity/www/raaf-page/web/uploads/property/'; //upload directory ends with / (slash)
                $jpeg_quality 			= 90; //jpeg quality
        ##########################################

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
                $new_file_name = $image_name_only. '_' .  rand(0, 9999999999) . '.' . $image_extension;

                //folder path to save resized images and thumbnails
                $thumb_save_folder 	= $destination_folder . $thumb_prefix . $new_file_name;
                $image_save_folder 	= $destination_folder . $new_file_name;

                //call normal_resize_image() function to proportionally resize image
                if($fileUploader->normalResizeImage($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality))
                {
                    //call crop_image_square() function to create square thumbnails
                    if(!$fileUploader->cropImageSquare($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality))
                    {
                        die('Error Creating thumbnail');
                    }

                    //$imagesDir = $this->get('kernel')->getRootDir() . '/../web/images';
                    $filePathTemp ='uploads/property/'.$thumb_prefix . $new_file_name;
                }

                //freeup memory
                imagedestroy($image_res);
            }
        }

        return array('path' => $filePathTemp);
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
