<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TeamRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function findAllIndexedById()
    {
        return $this->createQueryBuilder('t')
            ->indexBy('t', 't.id')
            ->getQuery()->getResult();
    }
}
