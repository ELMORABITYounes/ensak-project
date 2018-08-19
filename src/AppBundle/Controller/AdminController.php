<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Departement;
use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use AppBundle\Form\DepartementType;
use AppBundle\Form\EditDepartementType;
use AppBundle\Form\DepartementFieldType;
use AppBundle\Form\EditStudentType;
use AppBundle\Form\EditTeacherType;
use AppBundle\Form\NiveauType;
use AppBundle\Form\StudentType;
use AppBundle\Form\TeacherType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     *
     * @Route("/admin",name="adminHome")
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function indexAction()
    {
        return $this->render('Admin/index.html.twig');
    }

    /**
     * @Route("/admin/studentsList",name="studentsList")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function studentsListAction(){
        $form=$this->createForm(NiveauType::class);
        $students =$this->getDoctrine()->getRepository("AppBundle:Student")->findAll();
        return $this->render("Admin/Students/list.html.twig",array("students"=>$students,"form"=>$form->createView()));
    }

    /**
     * @Route("/admin/loadStudents",name="load")
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadAction(Request $request){
        $niveauId=$request->get("niveau");
        $form=$this->createForm(NiveauType::class);
        if ($niveauId!=null){
            $niveau =$this->getDoctrine()->getRepository("AppBundle:Niveau")->find($niveauId);
            $students =$this->getDoctrine()->getRepository("AppBundle:Student")->findBy(["niveau" => $niveau]);
            return $this->render("Admin/Students/liststudents.html.twig",array("students"=>$students,"form"=>$form->createView()));
        }
        $students =$this->getDoctrine()->getRepository("AppBundle:Student")->findAll();
        return $this->render("Admin/Students/liststudents.html.twig",array("students"=>$students,"form"=>$form->createView()));
    }

    /**
     * @Route("/admin/renderForm",name="renderForm")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderFormAction(Request $request){
        $id=$request->get("id");
        $student=$this->getDoctrine()->getRepository("AppBundle:Student")->find($id);
        $form=$this->createForm(EditStudentType::class,$student);
        return $this->render("Admin/Students/editform.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/editStudent",name="editStudent")
     * @Method({"POST"})
     */
    public function editStudentForm(Request $request)
    {
        $form=$this->createForm(EditStudentType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $id=$form->getData()["id"];
            $student=$entityManager->getRepository("AppBundle:Student")->find($id);
            if ($student->getEmail()!=$form->getData()["email"]){
                if($entityManager->getRepository("AppBundle:User")->isEmailExist($form->getData()["email"])){
                    $this->addFlash("error","modification imossible, email déja existant");
                    return $this->redirectToRoute('studentsList');
                }
            }

            if ($student->getCne()!=$form->getData()["cne"]){
                if($entityManager->getRepository("AppBundle:Student")->isCneExist($form->getData()["cne"])){
                    $this->addFlash("error","modification imossible, cne déja existant");
                    return $this->redirectToRoute('studentsList');
                }
            }
                $student->setEmail($form->getData()["email"]);
                $student->setFirstName($form->getData()["firstName"]);
                $student->setSecondName($form->getData()["secondName"]);
                $student->setCne($form->getData()["cne"]);
                $student->setTel($form->getData()["tel"]);
                $student->setNiveau($form->getData()["niveau"]);
                $student->setImageFile($form->getData()["imageFile"]);
                $this->addFlash("success","L'étudiant a été modifié correctement");
            $entityManager->persist($student);
            $entityManager->flush();
            return $this->redirectToRoute('studentsList');
        }
        $this->addFlash("error","Modification impossible vérifié que vous avez entrez des valeurs valides");
        return $this->redirectToRoute('studentsList');
    }

    /**
     * @Route("/admin/bloquerStudent",name="disableStudent")
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bloquerStudentAction(Request $request){
        $idStudent=$request->get("idStudent");
        if ($idStudent!=null){
            $student = $this->getDoctrine()->getRepository("AppBundle:User")->find($idStudent);
            $student->setEnabled(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();
            if($student instanceof Student)
            return $this->redirectToRoute("studentsList");
            else
                return $this->redirectToRoute("teachersList");
        }
        $this->addFlash("error","Une érreur est survenu");
        return $this->redirectToRoute("adminHome");
    }

    /**
     * @Route("/admin/debloquerEtudiant",name="enableStudent")
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function enableStudentAction(Request $request){
        $idStudent=$request->get("idStudent");
        if ($idStudent!=null){
            $student = $this->getDoctrine()->getRepository("AppBundle:User")->find($idStudent);
            $student->setEnabled(true);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();
            if($student instanceof Student)
            return $this->redirectToRoute("studentsList");
            else
            return $this->redirectToRoute("teachersList");
        }
        $this->addFlash("error","Une érreur est survenu");
        return $this->redirectToRoute("adminHome");
    }


    /**
     * @Route("/admin/addStudent",name="addStudent")
     */
    public function addStudent(Request $request)
    {
        $student=new Student();
        $form=$this->createForm(StudentType::class,$student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();
            $this->addFlash("success","L'étudiant a été ajouté avec succés");
            return $this->redirectToRoute("studentsList");
        }
        return $this->render("Admin/Students/addStudent.html.twig",array("form"=>$form->createView()));
    }


    /**
     * @Route("/admin/teachersList",name="teachersList")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function teachersListAction(){
        $form=$this->createForm(DepartementFieldType::class);
        $teachers =$this->getDoctrine()->getRepository("AppBundle:Teacher")->findAll();
        return $this->render("Admin/Teachers/list.html.twig",array("teachers"=>$teachers,"form"=>$form->createView()));
    }

    /**
     * @Route("/admin/renderTeacherForm",name="renderTeacherForm")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderTeacherFormAction(Request $request){
        $id=$request->get("id");
        $teacher=$this->getDoctrine()->getRepository("AppBundle:Teacher")->find($id);
        $form=$this->createForm(EditTeacherType::class,$teacher);
        return $this->render("Admin/Teachers/editform.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/loadTeachers",name="loadTeachers")
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadTeachersAction(Request $request){
        $depId=$request->get("dep");
        $form=$this->createForm(DepartementFieldType::class);
        if ($depId!=null){
            $dep =$this->getDoctrine()->getRepository("AppBundle:Departement")->find($depId);
            $teachers =$this->getDoctrine()->getRepository("AppBundle:Teacher")->findBy(["departement" => $dep]);
            return $this->render("Admin/Teachers/listteachers.html.twig",array("teachers"=>$teachers,"form"=>$form->createView()));
        }
        $teachers =$this->getDoctrine()->getRepository("AppBundle:Teacher")->findAll();
        return $this->render("Admin/Teachers/listteachers.html.twig",array("teachers"=>$teachers,"form"=>$form->createView()));
    }

    /**
     * @Route("/admin/editTeacher",name="editTeacher")
     * @Method({"POST"})
     */
    public function editTeacherForm(Request $request)
    {
        $form=$this->createForm(EditTeacherType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $id=$form->getData()["id"];
            $teacher=$entityManager->getRepository("AppBundle:Teacher")->find($id);
            if ($teacher->getEmail()!=$form->getData()["email"]){
                if($entityManager->getRepository("AppBundle:User")->isEmailExist($form->getData()["email"])){
                    $this->addFlash("error","modification imossible, email déja existant");
                    return $this->redirectToRoute('teachersList');
                }
            }

            if ($teacher->getSomme()!=$form->getData()["somme"]){
                if($entityManager->getRepository("AppBundle:Teacher")->isSommeExist($form->getData()["somme"])){
                    $this->addFlash("error","modification imossible, somme déja existant");
                    return $this->redirectToRoute('studentsList');
                }
            }
            $teacher->setEmail($form->getData()["email"]);
            $teacher->setFirstName($form->getData()["firstName"]);
            $teacher->setSecondName($form->getData()["secondName"]);
            $teacher->setSomme($form->getData()["somme"]);
            $teacher->setTel($form->getData()["tel"]);
            $teacher->setDepartement($form->getData()["departement"]);
            $teacher->setImageFile($form->getData()["imageFile"]);
            $this->addFlash("success","L'étudiant a été modifié correctement");
            $entityManager->persist($teacher);
            $entityManager->flush();
            return $this->redirectToRoute('teachersList');
        }
        $this->addFlash("error","Modification impossible vérifié que vous avez entrez des valeurs valides");
        return $this->redirectToRoute('teachersList');
    }

    /**
     * @Route("/admin/addTeacher",name="addTeacher")
     */
    public function addTeacher(Request $request)
    {
        $teacher=new Teacher();
        $form=$this->createForm(TeacherType::class,$teacher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($teacher);
            $entityManager->flush();
            $this->addFlash("success","Le professeur a été ajouté avec succés");
            return $this->redirectToRoute("teachersList");
        }
        return $this->render("Admin/Teachers/addTeacher.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/departmentsList",name="departmentsList")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function departmentsListAction(){
        $departements =$this->getDoctrine()->getRepository("AppBundle:Departement")->findAll();
        return $this->render("Admin/Departments/list.html.twig",array("departements"=>$departements));
    }


    /**
     * @Route("/admin/renderDepartmentForm",name="renderDepartmentForm")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderDepartmentFormAction(Request $request){
        $id=$request->get("id");
        $departement=$this->getDoctrine()->getRepository("AppBundle:Departement")->find($id);
        $form=$this->createForm(EditDepartementType::class,$departement);
        return $this->render("Admin/Departments/editform.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/editDepartement",name="editDepartement")
     * @Method({"POST"})
     */
    public function editDepartementForm(Request $request)
    {
        $form=$this->createForm(EditDepartementType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $id=$form->getData()["id"];
            $departement=$entityManager->getRepository("AppBundle:Departement")->find($id);
            if ($departement->getName()!=$form->getData()["name"]){
                if($entityManager->getRepository("AppBundle:Departement")->isNameExist($form->getData()["name"])){
                    $this->addFlash("error","modification imossible, nom déja existant");
                    return $this->redirectToRoute('departmentsList');
                }
            }
            $departement->setName($form->getData()["name"]);
            $departement->setDescription($form->getData()["description"]);
            $this->addFlash("success","Le département a été modifié correctement");
            $entityManager->persist($departement);
            $entityManager->flush();
        }
        return $this->redirectToRoute('departmentsList');
    }


    /**
     * @Route("/admin/addDepartement",name="addDepartement")
     */
    public function addDepartementAction(Request $request)
    {
        $dep=new Departement();
        $form=$this->createForm(DepartementType::class,$dep);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dep);
            $entityManager->flush();
            $this->addFlash("success","Le département a été ajouté avec succés");
            return $this->redirectToRoute("departmentsList");
        }
        return $this->render("Admin/Departments/addDepartement.html.twig",array("form"=>$form->createView()));
    }
}
