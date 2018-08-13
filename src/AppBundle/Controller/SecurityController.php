<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use AppBundle\Form\LoginType;
use AppBundle\Form\StudentRegisterType;
use AppBundle\Form\TeacherRegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
        $user=$this->getUser();

        if ($user instanceof Teacher){
            return $this->redirectToRoute("teacherHome");
        }elseif ($user instanceof Student){
            return $this->redirectToRoute("studentHome");
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

    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
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

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($teacher);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            $this->addFlash(
                'success',
                'Vous etes inscris avec succés!'
            );
            return $this->redirectToRoute('login');
        }

        return $this->render(
            'Security/register.html.twig',
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
                $password = $passwordEncoder->encodePassword($student, $student->getPlainPassword());
                $student->setPassword($password);

                // 4) save the User!
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($student);
                $entityManager->flush();

                // ... do any other work - like sending them an email, etc
                // maybe set a "flash" success message for the user
                $this->addFlash(
                    'success',
                    'Vous etes inscris avec succés!'
                );
                return $this->redirectToRoute('login');
            }

            return $this->render(
                'Security/register.html.twig',
                array('form' => $form->createView())
            );
        }
    }


    /**
     * @Route("/test")
     */
    public function testAction()
    {
        /*$entityManager = $this->getDoctrine()->getManager()->getRepository("AppBundle:Teacher");;
        $teacher=$entityManager->find(2);
        $helper=$this->get("vich_uploader.templating.helper.uploader_helper");
        $path = $helper->asset($teacher, 'imageFile');
*/
        return $this->render(
            'layout.html.twig'
           /* array('teacher' => $teacher
            ,'chemin'=>$path)*/
        );

    }
}
