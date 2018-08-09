<?php
/**
 * Created by PhpStorm.
 * User: ELMORABIT
 * Date: 08/08/2018
 * Time: 12:53
 */

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
    private $cne;
    private $filiere;


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

}