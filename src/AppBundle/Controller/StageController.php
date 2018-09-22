<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EncadrantExterne;
use AppBundle\Entity\Societe;
use AppBundle\Entity\Soutenance;
use AppBundle\Entity\Stage;
use AppBundle\Entity\StagePFA;
use AppBundle\Entity\StagePFE;
use AppBundle\Form\EditSocieteType;
use AppBundle\Form\EncadrantType;
use AppBundle\Form\SecteurActiviteFieldType;
use AppBundle\Form\SocieteType;
use AppBundle\Form\StagePFAType;
use AppBundle\Form\StagePFEType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StageController extends Controller
{

    /**
     * @Route("/admin/stagesList",name="stagesList")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stagesListAction()
    {
        $em=$this->getDoctrine()->getManager();
        $oldStages=$em->getRepository("AppBundle:Stage")->getOldStages();
        $newStages=$em->getRepository("AppBundle:Stage")->getNewStages();
        return $this->render('Admin/Stages/list.html.twig',array("oldStages"=>$oldStages,"newStages"=>$newStages));
    }

    /**
     * @Route("/admin/addSoutenance/{id}",name="addSoutenance",requirements={"id"="\d+"})
     *
     */
    public function addSoutenance(Request $request,StagePFE $stage){
        $soutenance=new Soutenance();
        $form=$this->createForm("AppBundle\Form\SoutenanceType",$soutenance);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $stage->setSoutenance($soutenance);
            $em->persist($stage);
            $em->flush();
            $this->addFlash("success","La soutenance à été ajouté corréctement");
            return $this->redirectToRoute("stagesList");
        }
        return $this->render("Admin/Stages/addSoutenance.html.twig",["form"=>$form->createView()]);
    }
    /**
     * @Route("/admin/editSoutenance/{id}",name="editSoutenance",requirements={"id"="\d+"})
     *
     */
    public function editSoutenance(Request $request,StagePFE $stage){
        $soutenance=$stage->getSoutenance();
        $form=$this->createForm("AppBundle\Form\SoutenanceType",$soutenance);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $stage->setSoutenance($soutenance);
            $em->persist($stage);
            $em->flush();
            $this->addFlash("success","La soutenance à été modifié corréctement");
            return $this->redirectToRoute("stagesList");
        }
        return $this->render("Admin/Stages/editSoutenance.html.twig",["form"=>$form->createView()]);
    }

    /**
     * @Route("admin/editStage/{id}",name="editStage" , requirements={"id"="\d+"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editStage(Request $request,Stage $stage){
        if ($stage instanceof StagePFE){
            $form=$this->createForm(StagePFEType::class,$stage);
        }else{
            $form=$this->createForm(StagePFAType::class,$stage);
        }
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
          $em=$this->getDoctrine()->getManager();
          $em->persist($stage);
          $em->flush();
          $this->addFlash("success","Le stage à été modifié corréctement");
          return $this->redirectToRoute("stagesList");
        }
        return $this->render("Admin/Stages/editStage.html.twig",["form"=>$form->createView()]);
    }


    /**
     * @Route("/admin/addStage",name="addStage")
     */
    public function addStageAction(Request $request)
    {
        $types = [
            'Formpfe' => "Stage PFE",
            'Formpfa' => "Stage PFA",
        ];
        $forms=array();
        $stagepfe=new StagePFE();
        $formpfe=$this->createForm(StagePFEType::class,$stagepfe);
        $forms[]=$formpfe;
        $stagepfa=new StagePFA();
        $formpfa=$this->createForm(StagePFAType::class,$stagepfa);
        $forms[]=$formpfa;
        if ($request->isMethod('POST')) {
            foreach ($forms as $form) {
                $form->handleRequest($request);

                if (!$form->isSubmitted()) continue; // no need to validate a form that isn't submitted

                if ($form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    if ($form == $formpfe){
                        $entityManager->persist($stagepfe);
                    }else{
                        $entityManager->persist($stagepfa);
                    }
                    $entityManager->flush();
                    $this->addFlash("success","Le stage a été ajouté avec succés");
                    return $this->redirectToRoute("stagesList");
                    break; // stop processing as we found the form we have to deal with
                }else{

                }
            }
        }
        $views = [];
        $views[]=$formpfe->createView();
        $views[]=$formpfa->createView();
        return $this->render("Admin/Stages/addStage.html.twig",array("forms"=>$views, 'types' => $types));
    }

    /**
     * @Route("/admin/societesList",name="societesList")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function societesListAction(){
        $form=$this->createForm(SecteurActiviteFieldType::class);
        $societes =$this->getDoctrine()->getRepository("AppBundle:Societe")->findAll();
        return $this->render("Admin/Societes/list.html.twig",array("societes"=>$societes,"form"=>$form->createView()));
    }

    /**
     * @Route("/admin/addSociete",name="addSociete")
     */
    public function addSocieteAction(Request $request)
    {
        $societe=new Societe();
        $form=$this->createForm(SocieteType::class,$societe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($societe);
            $entityManager->flush();
            $this->addFlash("success","La société a été ajouté avec succés");
            return $this->redirectToRoute("societesList");
        }
        return $this->render("Admin/Societes/addSociete.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/loadSocietes",name="loadSocietes")
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadSocietesAction(Request $request){
        $secteurId=$request->get("secteur");
        if ($secteurId!=null){
            $secteur =$this->getDoctrine()->getRepository("AppBundle:SecteurActivite")->find($secteurId);
            $societes =$this->getDoctrine()->getRepository("AppBundle:Societe")->loadSocieteBySecteur($secteur);
            return $this->render("Admin/Societes/listsocietes.html.twig",array("societes"=>$societes));
        }
        $societes =$this->getDoctrine()->getRepository("AppBundle:Societe")->findAll();
        return $this->render("Admin/Societes/listsocietes.html.twig",array("students"=>$societes));
    }

    /**
     * @Route("/admin/renderSocieteForm",name="renderSocieteForm")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderSocieteFormAction(Request $request){
        $id=$request->get("id");
        $societe=$this->getDoctrine()->getRepository("AppBundle:Societe")->find($id);
        $form=$this->createForm(EditSocieteType::class,$societe);
        return $this->render("Admin/Societes/editform.html.twig",array("form"=>$form->createView()));
    }

    /**
     * @Route("/admin/editSociete",name="editSociete")
     * @Method({"POST"})
     */
    public function editSocieteForm(Request $request)
    {
        $form=$this->createForm(EditSocieteType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $id = $form->getData()["id"];
            $societe=$entityManager->getRepository("AppBundle:Societe")->find($id);
            if ($societe->getName()!=$form->getData()["name"]){
                if($entityManager->getRepository("AppBundle:Societe")->isNameExist($form->getData()["name"])){
                    $this->addFlash("error","modification imossible, nom déja existant");
                    return $this->redirectToRoute('societesList');
                }
            }
            $societe->setName($form->getData()["name"]);
            $societe->setAddress($form->getData()["address"]);
            $societe->setVille($form->getData()["ville"]);
            $societe->setSecteursActivites($form->getData()["secteursActivites"]);
            $this->addFlash("success","La société a été modifié correctement");
            $entityManager->persist($societe);
            $entityManager->flush();
            return $this->redirectToRoute('societesList');
        }
        $this->addFlash("error","Modification impossible vérifié que vous avez entrez des valeurs valides");
        return $this->redirectToRoute('societesList');
    }

    /**
     *
     * @Route("admin/addEncadrant/{id}",name="addEncadrant")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function addEncadrantAction(Request $request,Societe $societe){
        $encadrant=new EncadrantExterne();
        $form=$this->createForm(EncadrantType::class,$encadrant,array("societe"=>$societe));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($encadrant);
            $entityManager->flush();
            $this->addFlash("success","L'encadrant a été ajouté avec succés");
            return $this->redirectToRoute("societesList");
        }
        return $this->render("Admin/Societes/addEncadrant.html.twig",array("form"=>$form->createView()));
    }

    /**
     *
     * @Route("admin/listEncadrants/{id}",name="listEncadrants")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function listEncadrantsAction(Societe $societe){
            $entityManager = $this->getDoctrine()->getManager();
            $encadrants=$entityManager->getRepository("AppBundle:EncadrantExterne")->findBy(array("societe"=>$societe));
            return $this->render("Admin/Societes/listEncadrants.html.twig",array("encadrants"=>$encadrants,"societe"=>$societe));
    }

    /**
     *
     * @Route("admin/editEncadrant/{id}",name="editEncadrant")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function editEncadrantAction(Request $request,EncadrantExterne $encadrantExterne){
        $form=$this->createForm(EncadrantType::class,$encadrantExterne,array("societe"=>$encadrantExterne->getSociete()));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($encadrantExterne);
            $entityManager->flush();
            $this->addFlash("success","L'encadrant a été modifié avec succés");
            return $this->redirectToRoute("societesList");
        }
        return $this->render("Admin/Societes/editEncadrant.html.twig",array("form"=>$form->createView()));
    }
}
