<?php
/**
 * Created by PhpStorm.
 * User: ELMORABIT
 * Date: 14/08/2018
 * Time: 22:01
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
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

    public function isEmailExist($email)
    {
        if($this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult()){
            return true;
        }else{
            return false;
        }
    }

}