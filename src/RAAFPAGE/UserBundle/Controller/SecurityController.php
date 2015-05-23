<?php

namespace RAAFPAGE\UserBundle\Controller;

use RAAFPAGE\UserBundle\Entity\User;
use RAAFPAGE\UserBundle\Form\Type\UserEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    /**
     * User login
     *
     * @Route("/login", name="login")
     * @return Response
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        //get login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('RAAFPAGEUserBundle:Security:login.html.twig',
            array(
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error' => $error
            )
        );
    }


    /**
     *
     * user registration
     *
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
            $form->handleRequest($request);

            if ($form->isValid()) {
                /** @var UserManager $userManager */
                $userManager = $this->get('raaf_page.user_bundle.user_manager');
                $userManager->handleUserRegistration($form->getData());

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
     * Activate user account
     *
     * @Route("/activate-account/{id}", name="activate_user_account")
     */
    public function activateAccountAction($id)
    {
        $userManager = $this->get('raaf_page.user_bundle.user_manager');
        $userManager->activateAccount($id);

        return $this->render('RAAFPAGEUserBundle:Email:activation.confirmation.html.twig');
    }

    /**
     * Update user account details
     *
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

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        die('logout');
    }
}
