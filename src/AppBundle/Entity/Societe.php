<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Societe
 *
 * @ORM\Table(name="societe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SocieteRepository")
 */
class Societe
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }


    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\SecteurActivite",cascade={"persist"})
     * @var ArrayCollection
     */
    private $secteursActivites;

    /**
     * @return ArrayCollection
     */
    public function getEncadrants()
    {
        return $this->encadrants->toArray();
    }


    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\EncadrantExterne",mappedBy="societe",cascade={"persist"})
     */
    private $encadrants;

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
     * @return Societe
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

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Societe
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->secteursActivites = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add secteursActivite
     *
     * @param \AppBundle\Entity\SecteurActivite $secteursActivite
     *
     * @return Societe
     */
    public function addSecteursActivite(\AppBundle\Entity\SecteurActivite $secteurActivite)
    {
        $this->secteursActivites[] = $secteurActivite;

        return $this;
    }

    /**
     * Remove secteursActivite
     *
     * @param \AppBundle\Entity\SecteurActivite $secteursActivite
     */
    public function removeSecteursActivite(\AppBundle\Entity\SecteurActivite $secteursActivite)
    {
        $this->secteursActivites->removeElement($secteursActivite);
    }

    /**
     * Get secteursActivites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSecteursActivites()
    {
        return $this->secteursActivites;
    }

    /**
     * @param ArrayCollection $secteursActivites
     */
    public function setSecteursActivites($secteursActivites)
    {
        $this->secteursActivites = $secteursActivites;
    }

    /**
     * Add encadrant
     *
     * @param \AppBundle\Entity\EncadrantExterne $encadrant
     *
     * @return Societe
     */
    public function addEncadrant(\AppBundle\Entity\EncadrantExterne $encadrant)
    {
        $this->encadrants[] = $encadrant;
        $encadrant->setSociete($this);
        return $this;
    }

    /**
     * Remove encadrant
     *
     * @param \AppBundle\Entity\EncadrantExterne $encadrant
     */
    public function removeEncadrant(\AppBundle\Entity\EncadrantExterne $encadrant)
    {
        $this->encadrants->removeElement($encadrant);
    }
}
