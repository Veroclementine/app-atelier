<?php

namespace App\Repository;

use App\Entity\TicketSolutions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TicketSolutions>
 *
 * @method TicketSolutions|null find($id, $lockMode = null, $lockVersion = null)
 * @method TicketSolutions|null findOneBy(array $criteria, array $orderBy = null)
 * @method TicketSolutions[]    findAll()
 * @method TicketSolutions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketSolutionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketSolutions::class);
    }

//    /**
//     * @return TicketSolutions[] Returns an array of TicketSolutions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TicketSolutions
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
