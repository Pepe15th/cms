<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return QueryBuilder
     */
    public function createBaseQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->addOrderBy('p.createdAt', 'asc');
    }

    /**
     * @return QueryBuilder
     */
    public function findPublished(): QueryBuilder
    {
        return $this->createBaseQueryBuilder()
            ->andWhere('p.publishedAt is not null');
    }

    /**
     * @param int $id
     * @return QueryBuilder
     */
    public function findPublishedById(int $id): QueryBuilder
    {
        return $this->createBaseQueryBuilder()
            ->andWhere('p.id = :id')
            ->andWhere('p.publishedAt is not null')
            ->setParameter('id', $id);
    }
}
