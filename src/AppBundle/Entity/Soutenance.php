<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Soutenance
 *
 * @ORM\Table(name="soutenance")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SoutenanceRepository")
 */
class Soutenance
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
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="dateSoutenance", type="datetime",nullable=true)
     */
    private $dateSoutenance;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MembreJury",cascade={"persist"},mappedBy="soutenance")
     */
    private $membres;

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
     * Set dateSoutenance
     *
     * @param \DateTime $dateSoutenance
     *
     * @return Soutenance
     */
    public function setDateSoutenance($dateSoutenance)
    {
        $this->dateSoutenance = $dateSoutenance;

        return $this;
    }

    /**
     * Get dateSoutenance
     *
     * @return \DateTime
     */
    public function getDateSoutenance()
    {
        return $this->dateSoutenance;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->membres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add membre
     *
     * @param \AppBundle\Entity\MembreJury $membre
     *
     * @return Soutenance
     */
    public function addMembre(\AppBundle\Entity\MembreJury $membre)
    {
        $this->membres[] = $membre;
        $membre->setSoutenance($this);

        return $this;
    }

    /**
     * Remove membre
     *
     * @param \AppBundle\Entity\MembreJury $membre
     */
    public function removeMembre(\AppBundle\Entity\MembreJury $membre)
    {
        $this->membres->removeElement($membre);
    }

    /**
     * Get membres
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembres()
    {
        return $this->membres;
    }
}
