<?php

namespace RAAFPAGE\FileManagerBundle\Controller;

use RAAFPAGE\FileManagerBundle\Entity\Asset;
use RAAFPAGE\FileManagerBundle\Entity\Document;
use RAAFPAGE\FileManagerBundle\Service\AssetManager;
use RAAFPAGE\FileManagerBundle\Service\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @template()
     */
    public function listAction()
    {
        $documentManager = $this->get('file_manager_bundle.document_manager');

        return array('documents' => $documentManager->getDocumentList());
    }

    /**
     * @Template()
     */
    public function uploadAction(Request $request)
    {
        $document = new Document();
        $form = $this->createFormBuilder($document)
            ->add('file')
            ->add('save','submit')
            ->getForm();

        $form->handleRequest($request);

        if ($_POST) {
            if ($form->isValid()){
                /** @var DocumentManager $documentManager*/
                $documentManager = $this->get('file_manager_bundle.document_manager');
                  $documentManager->upload($document);

                return $this->redirect($this->generateUrl('RAAFPAGE_file_manager_document_list'));
            }
        }

        return array('form' => $form->createView());
    }
}
