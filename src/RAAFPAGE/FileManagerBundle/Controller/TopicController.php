<?php
namespace RAAFPAGE\FileManagerBundle\Controller;

use RAAFPAGE\FileManagerBundle\Entity\Topic;
use RAAFPAGE\FileManagerBundle\Form\Type\TopicType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TopicController
 * @package RAAFPAGE\FileManagerBundle\Controller
 * @Route("/topic")
 */
class TopicController extends Controller
{
    /**
     * @Route("/list", name="topics")
     * @Method({"GET"})
     * @Template()
     */
    public function listAction()
    {
        return array();
    }

    /**
     * @Route("/new", name="add-new-topic")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function addAction(Request $request)
    {
        $topic = new Topic();
        $errors = null;

        $form = $this->createForm(new TopicType(), $topic, array(
            'action' => $this->generateUrl('add-new-topic')
        ));

        if ($_POST) {
            $entityManager = $this->getDoctrine()->getManager();
            $form->handleRequest($request);

            if ($form->isValid()) {
                $topic = $form->getData();

                $entityManager->persist($topic);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('topics'));
            } else {
                $errors = $form->getErrorsAsString();
            }
        }

        return array('form' => $form->createView(), 'errors' => $errors);
    }

} 