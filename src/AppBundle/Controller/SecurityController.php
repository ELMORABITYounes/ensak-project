<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     *
     * @Route("/login",name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form=$this->createForm(LoginType::class,["_username" => $lastUsername]);

        return $this->render('security/login.html.twig', array(
            'form' => $form->createView(),
            'error'         => $error,
        ));
    }

    /**
     *
     * @Route("/",name="homePage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        
        return $this->render('base.html.twig');
    }

    /**
     * @Route(
     *     "/register/{type}",
     *     defaults={"type": "student"},
     *     requirements={
     *         "profile": "student|teacher"
     *     },
     *     name="app_registration"
     * )
     * @Method({"POST"})
     */

    public function registerAction(Request $request)
    {

    }

}
