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
 * @ORM\Table(name="teacher")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeacherRepository")
 */
class Teacher extends User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @var integer
     */
    private $somme;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Departement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departement;


    /**
     * @return mixed
     */
    public function getDepartement()
    {
        return $this->departement;
    }
    /**
     * @param Departement $departement
     */
    public function setDepartement(Departement $departement)
    {
        $this->departement = $departement;
    }



    public function __construct()
    {
        $this->roles = array(static::ROLE_DEFAULT);
        $this->enabled = true;
        $this->addRole("ROLE_TEACHER");
    }

    /**
     * @return mixed
     */
    public function getSomme()
    {
        return $this->somme;
    }

    /**
     * @param mixed $somme
     */
    public function setSomme($somme)
    {
        $this->somme = $somme;
    }
}