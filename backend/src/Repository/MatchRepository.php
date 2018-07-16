<?php

namespace App\Repository;

use App\Entity\Match;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MatchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Match::class);
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function findAllIndexedById()
    {
        return $this->createQueryBuilder('m')
            ->indexBy('m', 'm.id')
            ->getQuery()->getResult();
    }
}