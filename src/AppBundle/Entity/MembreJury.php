<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * MembreJury
 *
 * @ORM\Table(name="membre_jury")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MembreJuryRepository")
 */
class MembreJury
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
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="secondName", type="string", length=255)
     */
    private $secondName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;


    /**
     * @var string
     *
     * @Assert\Choice(callback="getTypes")
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var Soutenance
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Soutenance",cascade={"persist"},inversedBy="membres")
     */
    private $soutenance;


    /**
     * @return Soutenance
     */
    public function getSoutenance()
    {
        return $this->soutenance;
    }

    /**
     * @param Soutenance $soutenance
     */
    public function setSoutenance($soutenance)
    {
        $this->soutenance = $soutenance;
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return MembreJury
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set secondName
     *
     * @param string $secondName
     *
     * @return MembreJury
     */
    public function setSecondName($secondName)
    {
        $this->secondName = $secondName;

        return $this;
    }

    /**
     * Get secondName
     *
     * @return string
     */
    public function getSecondName()
    {
        return $this->secondName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return MembreJury
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    public static function getTypes()
    {
        return array("Encadrant"=>"Encadrant", "Présidant"=>"Présidant", "Examinateur"=>"Examinateur");
    }


}

