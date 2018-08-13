<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends Controller
{
    /**
     *
     * @Route("/student",name="studentHome")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $student=$this->getUser();
        return $this->render('Student/index.html.twig',array("user" => $student));
    }
}
