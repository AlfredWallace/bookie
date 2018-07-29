<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function findAllIndexedById()
    {
        return $this->createQueryBuilder('p')
            ->indexBy('p', 'p.id')
            ->orderBy('p.points', 'desc')
            ->orderBy('p.username', 'asc')
            ->getQuery()->getResult();
    }
}
