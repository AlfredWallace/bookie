<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function findAllIndexedById()
    {
        return $this->createQueryBuilder('u')
            ->indexBy('u', 'u.id')
            ->orderBy('u.points', 'desc')
            ->orderBy('u.username', 'asc')
            ->getQuery()->getResult();
    }
}