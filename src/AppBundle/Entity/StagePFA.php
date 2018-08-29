<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StagePFA
 *
 * @ORM\Table(name="stage_p_f_a")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StagePFARepository")
 */
class StagePFA extends Stage
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

