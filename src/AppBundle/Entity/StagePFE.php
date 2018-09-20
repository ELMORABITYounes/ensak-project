<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @var Soutenance
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Soutenance",cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $soutenance;


    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if($this->dateDebut != null && $this->dateFin != null){
        $diff=$this->dateFin->diff($this->dateDebut);
        $months=$diff->m;
        if($months<4){
        $context->buildViolation("la durée d'un stage pfe doit etre au minimum 4 mois ")
            ->atPath('dateFin')
            ->addViolation();
        }
            if($months>6){
                $context->buildViolation("la durée d'un stage pfe ne peut pas dépasser 6 mois ")
                    ->atPath('dateFin')
                    ->addViolation();
            }
        }
    }

    /**
     * Set soutenance
     *
     * @param \AppBundle\Entity\Soutenance $soutenance
     *
     * @return StagePFE
     */
    public function setSoutenance(\AppBundle\Entity\Soutenance $soutenance = null)
    {
        $this->soutenance = $soutenance;

        return $this;
    }

    /**
     * Get soutenance
     *
     * @return \AppBundle\Entity\Soutenance
     */
    public function getSoutenance()
    {
        return $this->soutenance;
    }
}
