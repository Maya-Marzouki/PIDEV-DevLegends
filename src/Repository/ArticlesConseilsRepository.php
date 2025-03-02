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
    public function searchAndSort($searchTerm = null, $sortBy = 'titreArticle', $sortOrder = 'ASC')
    {
        $qb = $this->createQueryBuilder('a');

        if ($searchTerm) {
            $qb->andWhere('a.titreArticle LIKE :searchTerm OR a.contenuArticle LIKE :searchTerm OR a.categorieMentalArticle LIKE :searchTerm')
               ->setParameter('searchTerm', '%'.$searchTerm.'%');
        }

        $qb->orderBy('a.'.$sortBy, $sortOrder);

        return $qb->getQuery();
    }

    public function findAllOrderedByTitre($order = 'ASC')
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.titreArticle', $order)
            ->getQuery()
            ->getResult();
    }

    public function findByCriteria($criteria)
    {
        $queryBuilder = $this->createQueryBuilder('a');

        if (!empty($criteria['titre'])) {
            $queryBuilder->andWhere('a.titreArticle LIKE :titre')
                ->setParameter('titre', '%' . $criteria['titre'] . '%');
        }

        if (!empty($criteria['categorie'])) {
            $queryBuilder->andWhere('a.categorieMentalArticle = :categorie')
                ->setParameter('categorie', $criteria['categorie']);
        }

        if (!empty($criteria['order'])) {
            $queryBuilder->orderBy('a.titreArticle', $criteria['order']);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    // src/Repository/ArticlesConseilsRepository.php

public function findBySearchTerm(string $searchTerm): array
{
    return $this->createQueryBuilder('a')
        ->where('a.titre LIKE :searchTerm OR a.contenu LIKE :searchTerm')
        ->setParameter('searchTerm', '%' . $searchTerm . '%')
        ->orderBy('a.titre', 'ASC')
        ->getQuery()
        ->getResult();
}

public function searchArticles(?string $titre, ?string $categorie): array
{
    $qb = $this->createQueryBuilder('a');

    if ($titre) {
        $qb->andWhere('a.titreArticle LIKE :titre')
           ->setParameter('titre', '%' . $titre . '%');
    }

    if ($categorie) {
        $qb->andWhere('a.categorieMentalArticle = :categorie')
           ->setParameter('categorie', $categorie);
    }

    return $qb->getQuery()->getResult();
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
