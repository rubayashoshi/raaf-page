<?php

namespace RAAFPAGE\UserBundle\Controller;

use RAAFPAGE\UserBundle\Entity\User;
use RAAFPAGE\UserBundle\Form\Type\UserEditType;
use RAAFPAGE\UserBundle\Form\Type\UserType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * Company admin home page
     * @Route("/admin", name = "admin_home_page")
     * @return Response
     */
    public function homeAction()
    {
        return $this->render('RAAFPAGEUserBundle:Admin:admin.html.twig');
    }

    /**
     * Seller home page
     * @Route("/seller", name = "seller_home_page")
     * @return Response
     */
    public function sellerAction()
    {
        return $this->render('RAAFPAGEUserBundle:Admin:seller.html.twig');
    }

    /**
     * @param Request $request
     * @Route("/register", name = "add_account")
     * @return RedirectResponse|Response
     */
    public function addAccountAction(Request $request)
    {
        $user = new User();
        $errors = null;

        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl('add_account')
        ));

        if ($_POST) {
            $entityManager = $this->getDoctrine()->getManager();
            $form->handleRequest($request);

            if ($form->isValid()) {
                /**
                 * @var User $user
                 */
                $user = $form->getData();
                $password = $user->getPassword();

                // encode the password
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
                $user->setPassword($encodedPassword);

                $user->setRole('ROLE_USER');
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('edit_profile',array('id' => $user->getId())));
            } else {
                $errors = $form->getErrorsAsString();
            }
        }

        return $this->render(
            'RAAFPAGEUserBundle:Admin:register.html.twig',
            array('form' => $form->createView(), 'errors' => $errors)
        );
    }

    /**
     * @param Request $request
     * @param User $user
     * @Route("/user/profile/{id}", name = "edit_profile")
     * @return RedirectResponse|Response
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->createForm(new UserEditType(), $user, array(
            'action' => $this->generateUrl('add_account')
        ));
        $errors = false;

        if ($_POST) {
            $entityManager = $this->getDoctrine()->getManager();
            $form->handleRequest($request);

            if ($form->isValid()) {
                /**
                 * @var User $user
                 */
                $user = $form->getData();
                $password = $user->getPassword();

                // encode the password
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
                $user->setPassword($encodedPassword);

                $user->setRole('ROLE_USER');
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirect($this->generateUrl('ad_list'));
            } else {
                $errors = $form->getErrorsAsString();
            }
        }
        return $this->render(
            'RAAFPAGEUserBundle:Admin:edit.html.twig',
            array('form' => $form->createView(), 'errors' => $errors, 'user' => $user)
        );
    }
}
