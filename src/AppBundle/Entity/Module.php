<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleRepository")
 */
class Module
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
     * @ORM\Column(name="libelle", type="string", length=255, unique=true)
     */
    private $libelle;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrHCours", type="integer",nullable=true)
     */
    private $nbrHCours;

    /**
     * @var string
     *
     * @ORM\Column(name="nbrHTd", type="integer", nullable=true)
     */
    private $nbrHTd;


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
     * @return Module
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
     * Set nbrHCours
     *
     * @param integer $nbrHCours
     *
     * @return Module
     */
    public function setNbrHCours($nbrHCours)
    {
        $this->nbrHCours = $nbrHCours;

        return $this;
    }

    /**
     * Get nbrHCours
     *
     * @return int
     */
    public function getNbrHCours()
    {
        return $this->nbrHCours;
    }

    /**
     * Set nbrHTd
     *
     * @param string $nbrHTd
     *
     * @return Module
     */
    public function setNbrHTd($nbrHTd)
    {
        $this->nbrHTd = $nbrHTd;

        return $this;
    }

    /**
     * Get nbrHTd
     *
     * @return string
     */
    public function getNbrHTd()
    {
        return $this->nbrHTd;
    }
}

