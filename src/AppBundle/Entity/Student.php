<?php
/**
 * Created by PhpStorm.
 * User: ELMORABIT
 * Date: 08/08/2018
 * Time: 12:53
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @UniqueEntity(fields="cne", message="cne déja existant")
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 */
class Student extends User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        $this->roles = array(static::ROLE_DEFAULT);
        $this->enabled = true;
        $this->addRole("ROLE_STUDENT");
    }

    /**
     * @ORM\Column(type="bigint",unique=true)
     * @Assert\Length(min=10,max=10)
     * @var integer
     */
    private $cne;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Niveau")
     * @ORM\JoinColumn(nullable=false)
     */
    private $niveau;


    /**
     * @return mixed
     */
    public function getCne()
    {
        return $this->cne;
    }

    /**
     * @param mixed $cne
     */
    public function setCne($cne)
    {
        $this->cne = $cne;
    }

    /**
     * @return mixed
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @param mixed $niveau
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
    }

}