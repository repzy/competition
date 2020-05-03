<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findForDistance($id)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where($qb->expr()->eq('c.distance', ':distance'));
        $qb->leftJoin('c.children', 'children');
        $qb->leftJoin('c.parent', 'parent');

        $qb->setParameter('distance', $id);
        $query = $qb->getQuery();
        return $query->getResult();
    }
}