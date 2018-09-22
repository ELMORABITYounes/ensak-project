<?php
/**
 * Created by PhpStorm.
 * User: ELMORABIT
 * Date: 08/08/2018
 * Time: 15:37
 */

namespace AppBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class StudentRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        try {
            return $this->createQueryBuilder('u')
                ->where('u.email = :email')
                ->setParameter('email', $username)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function findByConfirmationToken($token)
    {
        try {
            return $this->createQueryBuilder('u')
                ->where('u.confirmationToken = :token')
                ->setParameter('token', $token)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }

    public function isCneExist($cne)
    {
        if($this->createQueryBuilder('u')
            ->where('u.cne = :cne')
            ->setParameter('cne', $cne)
            ->getQuery()
            ->getResult()){
            return true;
        }else{
            return false;
        }
    }

    public function countStudents()
    {
        return $this->createQueryBuilder('f')
            ->select('count(f)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}