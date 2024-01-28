<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Programmers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Programmers>
 *
 * @method Programmers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programmers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programmers[] findAll()
 * @method Programmers[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgrammersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programmers::class);
    }

//    /**
//     * @return Programmers[] Returns an array of Programmers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Programmers
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
