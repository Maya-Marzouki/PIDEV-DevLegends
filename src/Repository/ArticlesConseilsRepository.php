<?php

namespace App\Repository;

use App\Entity\ArticlesConseils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticlesConseils>
 */
class ArticlesConseilsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticlesConseils::class);
    }

     // Ajout de la méthode save()
     public function save(ArticlesConseils $article, bool $flush = true): void
     {
         $this->getEntityManager()->persist($article);
         if ($flush) {
             $this->getEntityManager()->flush();
         }
     }
 
     // Ajout de la méthode remove()
     public function remove(ArticlesConseils $article, bool $flush = true): void
     {
         $this->getEntityManager()->remove($article);
         if ($flush) {
             $this->getEntityManager()->flush();
         }
     }

    //    /**
    //     * @return ArticlesConseils[] Returns an array of ArticlesConseils objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ArticlesConseils
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}