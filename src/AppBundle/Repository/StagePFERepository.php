<?php

namespace AppBundle\Repository;

/**
 * StagePFERepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StagePFERepository extends \Doctrine\ORM\EntityRepository
{
    public function countStagesPFE()
    {
        return $this->createQueryBuilder('f')
            ->select('count(f)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
