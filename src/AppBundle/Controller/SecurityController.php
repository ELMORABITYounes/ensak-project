<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use AppBundle\Entity\User;
use AppBundle\Form\LoginType;
use AppBundle\Form\StudentRegisterType;
use AppBundle\Form\TeacherRegisterType;
use AppBundle\Util\TokenGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\TranslatorInterface;

class SecurityController extends Controller
{
    private $ttl;

    public function __construct()
    {
        $this->ttl=20000;
    }

    /**
     *
     * @Route("/login",name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_TEACHER')){
            return $this->redirectToRoute("teacherHome");
        }elseif ($this->get('security.authorization_checker')->isGranted('ROLE_STUDENT')){
            return $this->redirectToRoute("studentHome");
        }elseif ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute("adminHome");
        }
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
        if ($this->get('security.authorization_checker')->isGranted('ROLE_TEACHER')){
            return $this->redirectToRoute("teacherHome");
        }elseif ($this->get('security.authorization_checker')->isGranted('ROLE_STUDENT')){
            return $this->redirectToRoute("studentHome");
        }elseif ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute("adminHome");
        }else{
            return $this->redirectToRoute('login');
        }
    }

    /**
     * @Route(
     *     "/register/{type}",
     *     defaults={"type": "student"},
     *     requirements={
     *         "type": "student|teacher"
     *     },
     *     name="app_registration"
     * )
     * @Method({"POST"})
     */

    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder,TokenGenerator $tokenGenerator,TranslatorInterface $translator)
    {
        $type=$request->get("type");
        if ($type==="teacher"){
        // 1) build the form
        $teacher = new Teacher();
        $form = $this->createForm(TeacherRegisterType::class, $teacher);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($teacher, $teacher->getPlainPassword());
            $teacher->setPassword($password);
            $teacher->setEnabled(false);

            // 4) save the User!
            if (null === $teacher->getConfirmationToken()) {
                $teacher->setConfirmationToken($tokenGenerator->generateToken());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($teacher);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            $this->sendConfirmationMail($translator,$teacher);
            $this->addFlash(
                'success',
                "Un e-mail a été envoyé à l'adresse ".$teacher->getEmail().". Il contient un lien d'activation sur lequel il vous faudra cliquer afin d'activer votre compte."
            );
            return $this->redirectToRoute('login');
        }

        return $this->render(
            'Security/registration/register.html.twig',
            array('form' => $form->createView())
        );
        }else{
            // 1) build the form
            $student = new Student();
            $form = $this->createForm(StudentRegisterType::class, $student);

            // 2) handle the submit (will only happen on POST)
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                // 3) Encode the password (you could also do this via Doctrine listener)
                /*$password = $passwordEncoder->encodePassword($student, $student->getPlainPassword());
                $student->setPassword($password);*/

                // 4) save the User!
                if (null === $student->getConfirmationToken()) {
                    $student->setConfirmationToken($tokenGenerator->generateToken());
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($student);
                $entityManager->flush();

                // ... do any other work - like sending them an email, etc
                // maybe set a "flash" success message for the user

                $this->sendConfirmationMail($translator,$student);
                $this->addFlash(
                    'success',
                    "Un e-mail a été envoyé à l'adresse ".$student->getEmail().". Il contient un lien d'activation sur lequel il vous faudra cliquer afin d'activer votre compte."
                );
                return $this->redirectToRoute('login');
            }

            return $this->render(
                'Security/registration/register.html.twig',
                array('form' => $form->createView())
            );
        }
    }


    public function sendConfirmationMail(TranslatorInterface $translator,User $user){
        $url = $this->get("router")->generate('registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $message = (new \Swift_Message($translator->trans('registration.email.subject',array("%username%"=>$user->getFirstName()." ".$user->getSecondName()))))
            ->setFrom('gestion@filiere.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'Security/registration/email.txt.twig',
                    array('user' => $user,'confirmationUrl' => $url)
                ),
                'text/plain'
            );
        $this->get('mailer')->send($message);
    }

    /**
     * @Route("/confirm/{token}",name="registration_confirm")
     *
     * @Method({"GET"})
     */
    public function confirmAction(Request $request)
    {
        $token=$request->get("token");
        $user=$this->getDoctrine()->getRepository("AppBundle:User")->findByConfirmationToken($token);
        if (null === $user) {
            $this->addFlash("error","Lien invalide");
            return $this->redirectToRoute("login");
        }
        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash("success","Félicitations ".$user->getFirstName().", votre compte est maintenant activé.");
        return $this->redirectToRoute("login");
    }

    /**
     * @Route("/request",name="request")
     */
    public function reqeustAction(){
        return $this->render("Security/Resetting/forgot_password.html.twig");
    }

    /**
     * @Route("/reset",name="reset")
     * @Method({"POST"})
     */
    public function sendEmailAction(Request $request,TokenGenerator $tokenGenerator,TranslatorInterface $translator){
        $email=$request->get("email");
        $user=$this->getDoctrine()->getRepository("AppBundle:User")->loadUserByUsername($email);
        if(null !== $user && !$user->isPasswordRequestNonExpired($this->ttl)) {
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }

            $url = $this->get("router")->generate('resetting', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message($translator->trans('resetting.email.subject')))
                ->setFrom('gestion@filiere.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'Security/Resetting/email.txt.twig',
                        array('user' => $user,'confirmationUrl' => $url)
                    ),
                    'text/plain'
                );
            $this->get('mailer')->send($message);
            $user->setPasswordRequestedAt(new \DateTime());
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return new RedirectResponse($this->generateUrl('checkemail', array('username' => $email)));
    }

    /**
     * Tell the user to check his email provider.
     *
     * @Route("/checkEmail",name="checkemail")
     */
    public function checkEmailAction(Request $request,TranslatorInterface $translator)
    {
        $username = $request->query->get('username');
        if (empty($username)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->generateUrl('request'));
        }
        $this->addFlash("success",
            $translator->trans('resetting.check_email', array(
                    '%tokenLifetime%' => ceil($this->ttl / 3600))));
        return $this->redirectToRoute("login");
    }


    /**
     *
     * @Route("/resetaction/{token}",name="resetting")
     * @Method({"GET","POST"})
     * @param Request $request
     * @param $token
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function resetAction(Request $request,TranslatorInterface $translator, $token)
    {
        $user = $this->getDoctrine()->getRepository("AppBundle:User")->findByConfirmationToken($token);
        if (null === $user) {
            $this->addFlash("error",
                "Lien invalide");
            return new RedirectResponse($this->container->get('router')->generate('login'));
        }
        $form = $this->createFormBuilder($user)
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'messages',
                    'attr' => array(
                        'autocomplete' => 'new-password',
                    ),
                ),
                'first_options' => array('label' => 'form.new_password'),
                'second_options' => array('label' => 'form.new_password_confirmation'),
                'invalid_message' => 'les mots de passe ne sont pas identiques',
            ))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success",
                $translator->trans("resetting.flash.success"));
            $url = $this->generateUrl('login');
            $response = new RedirectResponse($url);
            return $response;
        }
        return $this->render('Security/Resetting/reset.html.twig', array(
            'token' => $token,
            'form' => $form->createView(),
        ));
    }

}
