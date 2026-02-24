<?php

namespace App\Repository;

use App\Entity\StockArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<StockArticle>
 */
class StockArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockArticle::class);
    }

    public function findOneBySameArticleAndArea(StockArticle $stockArticle): ?StockArticle
    {
       return $this->createQueryBuilder('sa')
            ->where('sa.article = :article')
            ->andWhere('sa.area = :area')
            ->andWhere('sa != :stockArticle')
            ->setParameter('article', $stockArticle->getArticle())
            ->setParameter('area', $stockArticle->getArea())
            ->setParameter('stockArticle', $stockArticle)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
