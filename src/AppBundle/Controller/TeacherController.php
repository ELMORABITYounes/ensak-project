<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TeacherController extends Controller
{
    /**
     * @Route("/teacher",name="teacherHome")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $teacher=$this->getUser();
        return $this->render('Teacher/index.html.twig',array("user" => $teacher));
    }
}
