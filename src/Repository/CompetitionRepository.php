<?php

namespace App\Repository;

use App\Entity\Competition;
use App\Entity\CompetitionClass;
use App\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Competition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competition[]    findAll()
 * @method Competition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetitionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Competition::class);
    }

    public function search(array $params)
    {
        $queryBuilder = $this->createQueryBuilder('c');

        if (!empty($params['name'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->like('c.name', ':name'));
            $queryBuilder->setParameter('name', '%' . $params['name'] . '%');
        }

        if (!empty($params['region']) && $params['region'] instanceof Region) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('c.region', ':region'));
            $queryBuilder->setParameter('region', $params['region']);
        }

        if (!empty($params['author'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq('c.author', ':author'));
            $queryBuilder->setParameter('author', $params['author']);
        }

        if (!empty($params['page']) && $params['page'] > 1) {
            $queryBuilder->setFirstResult(intval(($params['page'] - 1) * Competition::PER_PAGE));
        }

        $queryBuilder->setMaxResults(Competition::PER_PAGE);
        $queryBuilder->orderBy('c.date', 'desc');

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }

    // /**
    //  * @return Competition[] Returns an array of Competition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Competition
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
