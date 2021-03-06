<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Semestre
 *
 * @ORM\Table(name="semestre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SemestreRepository")
 */
class Semestre
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
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Niveau",inversedBy="semestres")
     * @ORM\JoinColumn(nullable=false)
     * @var Niveau
     */
    private $niveau;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Enseignement",mappedBy="semestre",cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $enseignements;

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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Semestre
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @return Niveau
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @param Niveau $niveau
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->enseignements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add enseignement
     *
     * @param \AppBundle\Entity\Enseignement $enseignement
     *
     * @return Semestre
     */
    public function addEnseignement(\AppBundle\Entity\Enseignement $enseignement)
    {
        $this->enseignements[] = $enseignement;
        $enseignement->setSemestre($this);
        return $this;
    }

    /**
     * Remove enseignement
     *
     * @param \AppBundle\Entity\Enseignement $enseignement
     */
    public function removeEnseignement(\AppBundle\Entity\Enseignement $enseignement)
    {
        $this->enseignements->removeElement($enseignement);
    }

    /**
     * Get enseignements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEnseignements()
    {
        return $this->enseignements;
    }

    /**
     * @param mixed $enseignements
     */
    public function setEnseignements($enseignements)
    {
        $this->enseignements = $enseignements;
    }
}
