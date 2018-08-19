<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Teacher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

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
}
