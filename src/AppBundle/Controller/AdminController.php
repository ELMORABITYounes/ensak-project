<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Departement;
use AppBundle\Entity\Enseignement;
use AppBundle\Entity\Filiere;
use AppBundle\Entity\Module;
use AppBundle\Entity\Niveau;
use AppBundle\Entity\Semestre;
use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use AppBundle\Form\AffectedModulesType;
use AppBundle\Form\DepartementType;
use AppBundle\Form\EditDepartementType;
use AppBundle\Form\DepartementFieldType;
use AppBundle\Form\EditModuleType;
use AppBundle\Form\EditStudentType;
use AppBundle\Form\EditTeacherType;
use AppBundle\Form\EnseignementsFieldType;
use AppBundle\Form\FiliereFieldType;
use AppBundle\Form\FiliereType;
use AppBundle\Form\ModuleFieldType;
use AppBundle\Form\ModuleType;
use AppBundle\Form\NiveauFieldType;
use AppBundle\Form\SemestreFieldType;
use AppBundle\Form\StudentType;
use AppBundle\Form\TeacherType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $em=$this->getDoctrine()->getManager();
        $nbrFilieres=$em->getRepository("AppBundle:Filiere")->countFilieres();
        $nbrStudents=$em->getRepository("AppBundle:Student")->countStudents();
        $nbrTeachers=$em->getRepository("AppBundle:Teacher")->countTeachers();
        $nbrSocietes=$em->getRepository("AppBundle:Societe")->countSocietes();
        $nbrModules=$em->getRepository("AppBundle:Module")->countModules();
        $nbrStages=$em->getRepository("AppBundle:Stage")->countStages();
        $nbrStagesPFE=$em->getRepository("AppBundle:StagePFE")->countStagesPFE();
        $nbrStagesPFA=$em->getRepository("AppBundle:StagePFA")->countStagesPFA();
        return $this->render('Admin/home.html.twig',[
            "nbrFilieres"=>$nbrFilieres,
            "nbrStudents"=>$nbrStudents,
            "nbrTeachers"=>$nbrTeachers,
            "nbrSocietes"=>$nbrSocietes,
            "nbrModules"=>$nbrModules,
            "nbrStages"=>$nbrStages,
            "nbrStagesPFE"=>$nbrStagesPFE,
            "nbrStagesPFA"=>$nbrStagesPFA
        ]);
    }

    /**
     * @Route("/admin/studentsList",name="studentsList")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function studentsListAction(){
        $form=$this->createForm(NiveauFieldType::class);
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
        if ($niveauId!=null){
            $niveau =$this->getDoctrine()->getRepository("AppBundle:Niveau")->find($niveauId);
            $students =$this->getDoctrine()->getRepository("AppBundle:Student")->findBy(["niveau" => $niveau]);
            return $this->render("Admin/Students/liststudents.html.twig",array("students"=>$students));
        }
        $students =$this->getDoctrine()->getRepository("AppBundle:Student")->findAll();
        return $this->render("Admin/Students/liststudents.html.twig",array("students"=>$students));
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
        $semestreForm=$this->createForm(SemestreFieldType::class);
        $form=$this->createForm(DepartementFieldType::class);
        $teachers =$this->getDoctrine()->getRepository("AppBundle:Teacher")->findAll();
        return $this->render("Admin/Teachers/list.html.twig",array("teachers"=>$teachers,"form"=>$form->createView(),"semestreForm"=>$semestreForm->createView()));
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
        if ($depId!=null){
            $dep =$this->getDoctrine()->getRepository("AppBundle:Departement")->find($depId);
            $teachers =$this->getDoctrine()->getRepository("AppBundle:Teacher")->findBy(["departement" => $dep]);
            return $this->render("Admin/Teachers/listteachers.html.twig",array("teachers"=>$teachers));
        }
        $teachers =$this->getDoctrine()->getRepository("AppBundle:Teacher")->findAll();
        return $this->render("Admin/Teachers/listteachers.html.twig",array("teachers"=>$teachers));
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


    /**
     * @Route("/admin/modulesList",name="modulesList")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modulesListAction(){
        $form=$this->createForm(DepartementFieldType::class);
        $modules =$this->getDoctrine()->getRepository("AppBundle:Module")->findAll();
        return $this->render("Admin/Modules/list.html.twig",array("modules"=>$modules,"form"=>$form->createView()));
    }



    /**
     * @Route("/admin/addModule",name="addModule")
     */
    public function addModuleAction(Request $request)
    {
        $module=new Module();
        $form=$this->createForm(ModuleType::class,$module);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($module);
            $entityManager->flush();
            $this->addFlash("success","Le module a été ajouté avec succés");
            return $this->redirectToRoute("modulesList");
        }
        return $this->render("Admin/Modules/addModule.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/downLoadCahier/{id}" ,name="downloadCahier")
     */
    public function downloadImageAction(Module $module)
    {
        $downloadHandler= $this->get("vich_uploader.download_handler");
        return $downloadHandler->downloadObject($module, $fileField = 'cahierFile');
    }

    /**
     * @Route("/admin/loadModules",name="loadModules")
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadModulesAction(Request $request){
        $id=$request->get("id");
        $departement=$this->getDoctrine()->getRepository("AppBundle:Departement")->find($id);
        if ($departement!=null){
            $modules =$this->getDoctrine()->getRepository("AppBundle:Module")->findBy(["departement" => $departement]);
            return $this->render("Admin/Modules/listmodules.html.twig",array("modules"=>$modules));
        }
        $modules =$this->getDoctrine()->getRepository("AppBundle:Module")->findAll();
        return $this->render("Admin/Modules/listmodules.html.twig",array("modules"=>$modules));
    }

    /**
     * @Route("/admin/showFiliere" ,name="showFiliere")
     */
    public function showFiliereAction(Request $request){
        $form=$this->createForm(FiliereFieldType::class);
        if($request->isMethod("POST")){
            $id=$request->get("id");
            if (isset($id)){
                $filiere=$this->getDoctrine()->getRepository("AppBundle:Filiere")->find($id);
                return $this->render("Admin/Filieres/listfilieres.html.twig",["filiere"=>$filiere]);
            }
        }
        return $this->render("Admin/Filieres/list.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/addFiliere",name="addFiliere")
     */
    public function addFiliereAction(Request $request)
    {
        $form=$this->createForm(FiliereType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filiere=new Filiere();
            $filiere->setName($form->getData()["name"]);
            $niveau1=new Niveau();
            $niveau1->setLibelle($form->getData()["premiere"]);
            $s1=new Semestre();
            $s1->setLibelle("S1");
            $s2=new Semestre();
            $s2->setLibelle("S2");
            $niveau1->addSemestre($s1);
            $niveau1->addSemestre($s2);
            $niveau2=new Niveau();
            $niveau2->setLibelle($form->getData()["deuxieme"]);
            $s3=new Semestre();
            $s3->setLibelle("S3");
            $s4=new Semestre();
            $s4->setLibelle("S4");
            $niveau2->addSemestre($s3);
            $niveau2->addSemestre($s4);
            $niveau3=new Niveau();
            $niveau3->setLibelle($form->getData()["troisieme"]);
            $s5=new Semestre();
            $s5->setLibelle("S5");
            $niveau3->addSemestre($s5);

            if ($form->getData()["nbrSemestres"]==6){
                $s6=new Semestre();
                $s6->setLibelle("S6");
                $niveau3->addSemestre($s6);
            }
            $filiere->addNiveau($niveau1);
            $filiere->addNiveau($niveau2);
            $filiere->addNiveau($niveau3);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($filiere);
            $entityManager->flush();
            $this->addFlash("success","La filière a été ajouté avec succés");
            return $this->redirectToRoute("showFiliere");
        }
        return $this->render("Admin/Filieres/addFiliere.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/renderEnseignementForm",name="renderEnseignementForm")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderEnseignementFormAction(Request $request){
        $id=$request->get("id");
        $semestre=$this->getDoctrine()->getRepository("AppBundle:Semestre")->find($id);
        $enseignements=$semestre->getEnseignements();
        $modules=array();
        foreach ($enseignements as $i => $enseignement)
        {
            $modules[]=$enseignement->getModule();
        }
        $form=$this->createForm(ModuleFieldType::class, array(), [ 'data'=> $modules]);
        return $this->render("Admin/Filieres/editEnseignements.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/setEnseeignements",name="setEnseeignements")
     * @Method({"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setEnseeignementsAction(Request $request){
        if($request->isMethod("POST")){
            $form=$this->createForm(ModuleFieldType::class,array(),["data"=>null]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em=$this->getDoctrine()->getManager();
                $idSemestre=$form->getData()["id"];
                $modules=$form->getData()["modules"];
                $semestre=$this->getDoctrine()->getRepository("AppBundle:Semestre")->find($idSemestre);
                $enseignements=$semestre->getEnseignements();
                $oldModules=new ArrayCollection();
                foreach ($enseignements as $i => $enseignement)
                {
                    $module=$enseignement->getModule();
                    if($modules->contains($module)){
                        $oldModules->add($module);
                    }else{
                        $semestre->removeEnseignement($enseignement);
                        $em->remove($enseignement);
                    }
                }
                dump($semestre);
                dump($oldModules);
                $newModules=new ArrayCollection();
                foreach ($modules as $i => $module)
                {
                    if (!$oldModules->contains($module)){
                        $newModules->add($module);
                    }
                }
                foreach ($newModules as $i => $module){
                    $enseignement=new Enseignement();
                    $enseignement->setModule($module);
                    $semestre->addEnseignement($enseignement);
                }
                dump($semestre);
                $em->persist($semestre);
                $em->flush();
                $this->addFlash("success","Modules enregistrés avec succes");
            }else
                $this->addFlash("error","Une erreur dans le formulaire");
        }
        return $this->redirectToRoute("showFiliere");
    }

    /**
     * @Route("/admin/renderModuleForm",name="renderModuleForm")
     */
    public function renderModuleFormAction(Request $request){
        $id=$request->get("id");
        $module=$this->getDoctrine()->getRepository("AppBundle:Module")->find($id);
        $form=$this->createForm(EditModuleType::class,$module);
        return $this->render("Admin/Modules/editmoduleform.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/editModule",name="editModule")
     */
    public function editModuleAction(Request $request){
        $form=$this->createForm(EditModuleType::class);
        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $id=$form->getData()["id"];
                $module=$entityManager->getRepository("AppBundle:Module")->find($id);
                if ($module->getLibelle()!=$form->getData()["libelle"]){
                    if($entityManager->getRepository("AppBundle:Module")->isLibelleExist($form->getData()["libelle"])){
                        $this->addFlash("error","modification imossible, nom déja existant");
                        return $this->redirectToRoute('modulesList');
                    }
                }
                $module->setLibelle($form->getData()["libelle"]);
                $module->setDepartement($form->getData()["departement"]);
                $module->setNbrHCours($form->getData()["nbrHCours"]);
                $module->setNbrHTd($form->getData()["nbrHTd"]);
                $module->setCahierFile($form->getData()["cahierFile"]);
                $this->addFlash("success","Le module a été modifié correctement");
                $entityManager->persist($module);
                $entityManager->flush();
            }
        return $this->redirectToRoute("modulesList");
    }

    /**
     * @Route("/admin/renderNotAffectedModules",name="renderNotAffectedModules")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderNotAffectedModulesAction(Request $request){
        $idSemestre=$request->get("idSemestre");
        $em=$this->getDoctrine()->getManager();
        $semestre=$em->getRepository("AppBundle:Semestre")->find($idSemestre);
        $enseignements=$semestre->getEnseignements();
        $modules=array();
        foreach ($enseignements as $i => $enseignement)
        {
            if($enseignement->getTeacher()===null){
                $module=$enseignement->getModule();
                $modules[]=$module;
            }
        }
        $form=$this->createForm(EnseignementsFieldType::class,array(),["modules"=>$modules,'csrf_protection' => false]);
        return $this->render("Admin/Teachers/notAffectedModulesField.html.twig",["form"=>$form->createView()]);
    }

    /**
     * @Route("/admin/affectModules",name="affectModules")
     */
    public function affectModulesAction(Request $request){
        $form=$this->createForm(EnseignementsFieldType::class,array(),['csrf_protection' => false]);
        $semestreForm=$this->createForm(SemestreFieldType::class);
        $form->handleRequest($request);
        $semestreForm->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $semestreForm->isSubmitted() && $semestreForm->isValid()) {
            $id=$semestreForm->getData()["id"];
            $modules=$form->getData()["modules"];
            $semestre=$semestreForm->getData()["semestre"];
            $em=$this->getDoctrine()->getManager();
            $teacher=$em->getRepository("AppBundle:Teacher")->find($id);
            foreach ($modules as $module){
                $enseignement=$em->getRepository("AppBundle:Enseignement")->findOneBy(array("module"=>$module,"semestre"=>$semestre));
                $enseignement->setTeacher($teacher);
                $em->persist($enseignement);
            }
            $em->flush();
            $this->addFlash("success","modules Affectés correctement.");
        }else{
            $this->addFlash("error","form invalid.");
        }
        return $this->redirectToRoute("teachersList");
    }

    /**
     * @Route("/admin/renderAffectedModules",name="renderAffectedModules")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderAffectedModulesAction(Request $request){
        $idTeacher=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $teacher=$em->getRepository("AppBundle:Teacher")->find($idTeacher);
        $enseignements=$em->getRepository("AppBundle:Enseignement")->findBy(array("teacher"=>$teacher));
        $modules=array();
        foreach ($enseignements as $i => $enseignement)
        {
                $module=$enseignement->getModule();
                $modules[]=$module;
        }
        $form=$this->createForm(AffectedModulesType::class,array(),["modules"=>$modules,"data"=>$modules]);
        return $this->render("Admin/Teachers/affectedModules.html.twig",["form"=>$form->createView()]);
    }

    /**
     * @Route("/admin/detacherModules",name="detacherModules")
     */
    public function detacherModulesAction(Request $request){
        $form=$this->createForm(AffectedModulesType::class,array(),["modules"=>null,"data"=>null]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$this->getDoctrine()->getManager();
            $id=$form->getData()["id"];
            $modules=$form->getData()["modules"];
            $teacher=$em->getRepository("AppBundle:Teacher")->find($id);
            $enseignements=$em->getRepository("AppBundle:Enseignement")->findBy(array("teacher"=>$teacher));
            foreach ($enseignements as $i => $enseignement)
            {
                $module=$enseignement->getModule();
                if (!$modules->contains($module)){
                    $enseignement->setTeacher(null);
                    $em->persist($enseignement);
                }
            }
            $this->addFlash("success","modification enregistré avec succés");
            $em->flush();
        }else{
            $this->addFlash("error","erreur dans le formulaire");
        }
        return $this->redirectToRoute("teachersList");
    }

    /**
     * @Route("/admin/deleteModule",name="deleteModule")
     */
    public function deleteModuleAction(Request $request){
        $idModule=$request->get("idModule");
        $em=$this->getDoctrine()->getManager();
        $module=$em->getRepository("AppBundle:Module")->find("$idModule");
        $em->remove($module);
        $em->flush();
        $this->addFlash("success","Module suprimer avec succés.");
        return $this->redirectToRoute("modulesList");
    }

}
