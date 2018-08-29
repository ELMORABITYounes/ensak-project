<?php
/**
 * Created by PhpStorm.
 * User: ELMORABIT
 * Date: 08/08/2018
 * Time: 15:57
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Departement;
use AppBundle\Entity\Filiere;
use AppBundle\Entity\Module;
use AppBundle\Entity\Niveau;
use AppBundle\Entity\SecteurActivite;
use AppBundle\Entity\Semestre;
use AppBundle\Entity\Student;
use AppBundle\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;
    private $entitymanager;

    public function __construct(UserPasswordEncoderInterface $encoder,EntityManagerInterface $entityManager)
    {
        $this->encoder = $encoder;
        $this->entitymanager = $entityManager;
    }
    /*
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager

    public function load(ObjectManager $manager)
    {
        for ($i=0;$i<4;$i++){
            $dep=new Departement();
            $dep->setName("departement ".$i);
            $dep->setDescription("description ".$i);
            $filiere=new Filiere();
            $filiere->setName("Filiere ".$i);
            $niveau1=new Niveau();
            $niveau1->setLibelle("1 ere année"." Filiere ".$i);
            $s1=new Semestre();
            $s1->setLibelle("S1");
            $s2=new Semestre();
            $s2->setLibelle("S2");
            $niveau1->addSemestre($s1);
            $niveau1->addSemestre($s2);
            $niveau2=new Niveau();
            $niveau2->setLibelle("2 ème année"." Filiere ".$i);
            $s3=new Semestre();
            $s3->setLibelle("S3");
            $s4=new Semestre();
            $s4->setLibelle("S4");
            $niveau2->addSemestre($s3);
            $niveau2->addSemestre($s4);
            $niveau3=new Niveau();
            $niveau3->setLibelle("3 ème année"." Filiere ".$i);
            $s5=new Semestre();
            $s5->setLibelle("S5");
            $niveau3->addSemestre($s5);
            $filiere->addNiveau($niveau1);
            $filiere->addNiveau($niveau2);
            $filiere->addNiveau($niveau3);
            $manager->persist($filiere);
            $manager->persist($dep);
            $manager->flush();
        }


        for ($i=0;$i<50;$i++){
            $module=new Module();
            $deps=$this->entitymanager->getRepository("AppBundle:Departement")->findAll();
            $module->setLibelle("module ".$i);
            $module->setNbrHTd("14");
            $module->setNbrHCours("36");
            $module->setDepartement($deps[random_int(0,3)]);
            $manager->persist($module);
            $manager->flush();
        }

        for ($i=0;$i<200;$i++){
            $student=new Student();
            $niveaux=$this->entitymanager->getRepository("AppBundle:Niveau")->findAll();
            $student->setEmail("student".$i."@gmail.com");
            $student->setFirstName("prénom".$i);
            $student->setSecondName("nom".$i);
            do{
                $cne=random_int(1000000000,9999999999);
            }while($manager->getRepository("AppBundle:Student")->isCneExist($cne));
            $student->setCne($cne);
            $student->setNiveau($niveaux[random_int(0,11)]);
            $student->setPlainPassword($student->getCne());
            $student->setTel("(+212)6".random_int(10000000,99999999));
            $manager->persist($student);
            $manager->flush();
        }

        for ($i=0;$i<30;$i++){
            $teacher=new Teacher();
            $deps=$manager->getRepository("AppBundle:Departement")->findAll();
            $teacher->setEmail("teacher".$i."@gmail.com");
            $teacher->setFirstName("prénom".$i);
            $teacher->setSecondName("nom".$i);
            do{
                $somme=random_int(1000000000,9999999999);
            }while($manager->getRepository("AppBundle:Teacher")->isSommeExist($somme));
            $teacher->setSomme($somme);
            $teacher->setDepartement($deps[random_int(0,3)]);
            $teacher->setPlainPassword($teacher->getSomme());
            $teacher->setTel("(+212)6".random_int(10000000,99999999));
            $manager->persist($teacher);
            $manager->flush();
        }
    }
    */
    public function load(ObjectManager $manager)
    {
        for ($i=0;$i<10;$i++) {
            $sector = new SecteurActivite();
            $sector->setName("Secteur " . $i);
            $manager->persist($sector);
            $manager->flush();
        }
    }
}

