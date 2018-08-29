<?php

namespace AppBundle\Repository;

/**
 * SocieteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SocieteRepository extends \Doctrine\ORM\EntityRepository
{
    public function loadSocieteBySecteur($secteur)
    {
        return $this->createQueryBuilder('s')
            ->where(":secteur MEMBER OF s.secteursActivites")
            ->setParameter('secteur', $secteur)
            ->getQuery()
            ->getResult();
    }

    public function isNameExist($name)
    {
        if($this->createQueryBuilder('u')
            ->where('u.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult()){
            return true;
        }else{
            return false;
        }
    }
}