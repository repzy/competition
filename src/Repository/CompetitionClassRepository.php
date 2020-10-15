<?php

namespace App\Repository;

use App\Entity\CompetitionClass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CompetitionClass|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetitionClass|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetitionClass[]    findAll()
 * @method CompetitionClass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetitionClassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetitionClass::class);
    }

    // /**
    //  * @return CompetitionClass[] Returns an array of CompetitionClass objects
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
    public function findOneBySomeField($value): ?CompetitionClass
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
