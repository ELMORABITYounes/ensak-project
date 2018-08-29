<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StagePFE
 *
 * @ORM\Table(name="stage_p_f_e")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StagePFERepository")
 */
class StagePFE extends Stage
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
     *
     * @ORM\Column(name="dateSoutenance", type="datetime",nullable=true)
     */
    private $dateSoutenance;


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
     * @return StagePFE
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
}

