<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Programmers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryInterface;
use Sulu\Component\SmartContent\Orm\DataProviderRepositoryTrait;

/**
 * @extends ServiceEntityRepository<Programmers>
 *
 * @method Programmers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programmers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programmers[] findAll()
 * @method Programmers[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgrammersRepository extends ServiceEntityRepository implements DataProviderRepositoryInterface
{
    use DataProviderRepositoryTrait {
        findByFilters as parentFindByFilters;
    }
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programmers::class);
    }

    public function findById(int $id, string $locale): ?Programmers
    {
        $programmer = $this->find($id);
        if (!$programmer instanceof Programmers) {
            return null;
        }
        return $programmer;
    }
    public function findByFilters($filters, $page, $pageSize, $limit, $locale, $options = [])
    {
        $entities = $this->parentFindByFilters($filters, $page, $pageSize, $limit, $locale, $options);

        return \array_map(
            fn (Programmers $entity) => $entity->setLocale($locale),
            $entities,
        );
    }
    protected function appendJoins(QueryBuilder $queryBuilder, $alias, $locale): void
    {
        $queryBuilder
            ->leftJoin('App\Entity\Programmers', 'p', 'WITH', "{$alias}.programmer = p.id")
            ->addSelect('p');    }

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
