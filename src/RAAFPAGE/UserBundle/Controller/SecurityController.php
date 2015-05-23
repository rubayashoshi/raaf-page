<?php

namespace RAAFPAGE\UserBundle\Controller;

use RAAFPAGE\UserBundle\Entity\User;
use RAAFPAGE\UserBundle\Form\Type\UserEditType;
use RAAFPAGE\UserBundle\Form\Type\UserType;
use RAAFPAGE\UserBundle\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Security\Core\Exception\DisabledException;
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
        $showActivationLink = false;
        $request = $this->getRequest();
        $session = $request->getSession();

        //get login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        if ($error instanceof DisabledException) {
            $showActivationLink = true;
        }

        return $this->render('RAAFPAGEUserBundle:Security:login.html.twig',
            array(
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error' => $error,
                'showActivationLink' => $showActivationLink
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
        $registrationSuccessMessage = false;

        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl('add_account')
        ));

        if ($_POST) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                /** @var UserManager $userManager */
                $userManager = $this->get('raaf_page.user_bundle.user_manager');

                if (! $userManager->checkUserAlreadyExists($form->getData())) {
                    $errors = $userManager->checkUserAlreadyExists($form->getData());
                    $userManager->handleUserRegistration($form->getData());
                    $registrationSuccessMessage = 'Your sign-up is completed, Please check your email and ' .
                        'click on the link to activate account';
                    //return $this->redirect($this->generateUrl('edit_profile',array('id' => $user->getId())));
                } else {
                    $error = 'The username/email is already exists';
                }
            } else {
                $error = $form->getErrorsAsString();
            }
        }

        return $this->render(
            'RAAFPAGEUserBundle:Admin:register.html.twig',
            array(
                'form' => $form->createView(),
                'error' => $error,
                'registrationSuccessMessage' => $registrationSuccessMessage
            )
        );
    }

    /**
     * Activate user account
     *
     * @Route("/account-activation-request", name="account_activation_request")
     */
    public function accountActivationRequestAction()
    {
        $status = false;
        $error = false;

        if (isset($_POST['email'])) {
            $email = $_POST['email'];
            $user = $this->getDoctrine()->getRepository('RAAFPAGEUserBundle:User')
                ->findOneBy(array('email' => $email));

            if ($user instanceof User) {
                $mailSender = $this->get('user_bundle.service.mail_sender');
                $user->setActivationEmailSent(new \DateTime('NOW'));
                $mailSender->sendMail($user);
                $status = 'success';
            } else {
                $error = 'This email is not registered with us';
            }
        }

        return $this->render('RAAFPAGEUserBundle:Security:activation.request.html.twig',
            array('status' => $status, 'error' => $error)
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
                /*
                 * //todo password changed should be part of separate submit
                $password = $user->getPassword();
                // encode the password
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $encodedPassword = $encoder->encodePassword($password, $user->getSalt());
                $user->setPassword($encodedPassword);

                $user->setRole('ROLE_USER');
                */
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
