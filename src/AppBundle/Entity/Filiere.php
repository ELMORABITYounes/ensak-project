<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Filiere
 *
 * @ORM\Table(name="filiere")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FiliereRepository")
 */
class Filiere
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Niveau",mappedBy="filiere",cascade={"persist"})
     * @var Niveau
     */
    private $niveaux;

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
     * Set name
     *
     * @param string $name
     *
     * @return Filiere
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function __toString(){
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->niveaux = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add niveaux
     *
     * @param \AppBundle\Entity\Niveau $niveaux
     *
     * @return Filiere
     */
    public function addNiveau(\AppBundle\Entity\Niveau $niveau)
    {
        $this->niveaux[] = $niveau;
        $niveau->setFiliere($this);
        return $this;
    }

    /**
     * Remove niveaux
     *
     * @param \AppBundle\Entity\Niveau $niveaux
     */
    public function removeNiveau(\AppBundle\Entity\Niveau $niveau)
    {
        $this->niveaux->removeElement($niveau);
    }

    /**
     * Get niveaux
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNiveaux()
    {
        return $this->niveaux;
    }
}
