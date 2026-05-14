<?php

namespace App\Repository;

use App\Entity\Kudos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Kudos>
 */
class KudosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kudos::class);
    }

    public function findByName(string $query): array{

        return $this->createQueryBuilder('k')
        ->join('k.sender', 's')
        ->join('k.receiver', 'r')
        ->where("CONCAT(r.firstName, ' ', r.lastName) LIKE :query")
        ->orWhere('r.firstName LIKE :query')
        ->orWhere('r.lastName LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();

    }

}
