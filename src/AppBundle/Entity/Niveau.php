<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Niveau
 *
 * @ORM\Table(name="niveau")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NiveauRepository")
 */
class Niveau
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
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Filiere",cascade={"persist"},inversedBy="niveaux")
     * @ORM\JoinColumn(nullable=false)
     *
     */

    private $filiere;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Semestre",mappedBy="niveau",cascade={"persist"})
     * @var Semestre
     */
    private $semestres;




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
     * @return Niveau
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
     * @return mixed
     */
    public function getFiliere()
    {
        return $this->filiere;
    }

    /**
     * @param mixed $filiere
     */
    public function setFiliere($filiere)
    {
        $this->filiere = $filiere;
    }


    /**
     *
     * @return string
     */
    public function _toString()
    {
        return $this->libelle;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->semestres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add semestre
     *
     * @param \AppBundle\Entity\Semestre $semestre
     *
     * @return Niveau
     */
    public function addSemestre(\AppBundle\Entity\Semestre $semestre)
    {
        $this->semestres[] = $semestre;
        $semestre->setNiveau($this);
        return $this;
    }

    /**
     * Remove semestre
     *
     * @param \AppBundle\Entity\Semestre $semestre
     */
    public function removeSemestre(\AppBundle\Entity\Semestre $semestre)
    {
        $this->semestres->removeElement($semestre);
    }

    /**
     * Get semestres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSemestres()
    {
        return $this->semestres;
    }
}
