<?php

namespace App\Repository;

use App\Entity\Competition;
use App\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Competition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competition[]    findAll()
 * @method Competition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetitionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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

    public function findAllIds()
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c.id');
        $queryBuilder->orderBy('c.id', 'DESC');

        return $queryBuilder->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}
