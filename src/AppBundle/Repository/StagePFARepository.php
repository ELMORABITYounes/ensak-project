<?php

namespace AppBundle\Repository;

/**
 * StagePFARepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StagePFARepository extends \Doctrine\ORM\EntityRepository
{
    public function countStagesPFA()
    {
        return $this->createQueryBuilder('f')
            ->select('count(f)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
