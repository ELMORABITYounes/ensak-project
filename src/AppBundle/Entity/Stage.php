<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Stage
 *
 * @ORM\Table(name="stage")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"StagePFA" = "StagePFA", "StagePFE" = "StagePFE"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StageRepository")
 */
abstract class Stage
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="sujet", type="string", length=255)
     */
    protected $sujet;

    /**
     * @var \DateTime
     * @Assert\Date()
     * @Assert\NotBlank()
     * @ORM\Column(name="dateDebut", type="date")
     */
    protected $dateDebut;

    /**
     * @var \DateTime
     * @Assert\Date()
     * @Assert\NotBlank()
     * @ORM\Column(name="dateFin", type="date")
     */
    protected $dateFin;

    /**
     * @var array
     * @Assert\NotBlank()
     * @ORM\Column(name="technologies", type="string")
     */
    protected $technologies;

    /**
     * @var EncadrantExterne
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EncadrantExterne",cascade={"persist"})
     */
    protected $encadrantExtern;

    /**
     * @var Societe
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Societe",cascade={"persist"})
     *
     */
    protected $societe;


    /**
     * @var Teacher
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Teacher",cascade={"persist"})
     *
     */
    protected $professeurEncadrant;

    /**
     * @return EncadrantExterne
     */
    public function getEncadrantExtern()
    {
        return $this->encadrantExtern;
    }


    /**
     * @var Student;
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Student",cascade={"persist"})
     *
     */
    private $student;

    /**
     * @return Student
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * @param Student $student
     */
    public function setStudent($student)
    {
        $this->student = $student;
    }

    /**
     * @param EncadrantExterne $encadrantExtern
     */
    public function setEncadrantExtern($encadrantExtern)
    {
        $this->encadrantExtern = $encadrantExtern;
    }

    /**
     * @return Teacher
     */
    public function getProfesseurEncadrant()
    {
        return $this->professeurEncadrant;
    }

    /**
     * @param Teacher $professeurEncadrant
     */
    public function setProfesseurEncadrant($professeurEncadrant)
    {
        $this->professeurEncadrant = $professeurEncadrant;
    }
    /**
     * @return EncadrantExterne
     */
    public function getEncadrant()
    {
        return $this->encadrantExtern;
    }

    /**
     * @param EncadrantExterne $encadrant
     */
    public function setEncadrant($encadrant)
    {
        $this->encadrantExtern = $encadrant;
    }

    /**
     * @return Societe
     */
    public function getSociete()
    {
        return $this->societe;
    }

    /**
     * @param Societe $societe
     */
    public function setSociete($societe)
    {
        $this->societe = $societe;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sujet
     *
     * @param string $sujet
     *
     * @return Stage
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Stage
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }


    /**
     * Set technologies
     *
     * @param array $technologies
     *
     * @return Stage
     */
    public function setTechnologies($technologies)
    {
        $this->technologies = $technologies;

        return $this;
    }

    /**
     * Get technologies
     *
     * @return array
     */
    public function getTechnologies()
    {
        return $this->technologies;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Stage
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }
}
