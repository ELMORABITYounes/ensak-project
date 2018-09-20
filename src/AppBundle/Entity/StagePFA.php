<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if($this->dateDebut != null && $this->dateFin != null){
            $diff=$this->dateFin->diff($this->dateDebut);
            $months=$diff->m;
            if($months<1){
                $context->buildViolation("la durée d'un stage pfe doit etre au minimum un mois ")
                    ->atPath('dateFin')
                    ->addViolation();
            }
        }
    }
}

