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

    public function findFutureMatches()
    {
        return $this->createQueryBuilder('m')
            ->where('m.kickOff > :now')
            ->orderBy('m.kickOff', 'asc')
            ->setParameter('now', new \DateTime())
            ->getQuery()->getResult();
    }

    public function findStartedMatches()
    {
        return $this->createQueryBuilder('m')
            ->where('m.kickOff < :now')
            ->orderBy('m.kickOff', 'desc')
            ->setParameter('now', new \DateTime())
            ->getQuery()->getResult();
    }
}