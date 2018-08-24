<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Module;
use AppBundle\Entity\Teacher;
use AppBundle\Form\ModuleFieldType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;

class TestController extends Controller
{
    /**
     * @Route("/email",name="test")
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction( \Swift_Mailer $mailer)
    {
        $user =new Teacher();
        $user->setFirstName("younes");
        $response=$this->render("Security/registration/email.txt.twig",array("user"=>$user,"confirmationUrl"=>"test"));
        $response->headers->set('Content-Type', 'text/plain');
        return $response;
    }

    /**
     * @Route("/test")
     */
    function testAction(){
        $semestre=$this->getDoctrine()->getRepository("AppBundle:Semestre")->find(10);
        $enseignements=$semestre->getEnseignements();
        $modules=array();
        foreach ($enseignements as $i => $enseignement)
        {
            $modules[]=$enseignement->getModule();
        }
        $form=$this->createForm(ModuleFieldType::class, array(), [ 'data'=> $modules ]);
        return $this->render("/Security/test.html.twig",array("form"=>$form->createView()));
    }


}
