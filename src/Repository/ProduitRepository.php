<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }
    public function findBySearch(?string $searchTerm, ?string $selectedCategorie)
    {
        $qb = $this->createQueryBuilder('p');

        // Filtrer par nom du produit
        if (!empty($searchTerm)) {
            $qb->andWhere('p.nomProduit LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        // Filtrer par catégorie si une catégorie est sélectionnée
        if (!empty($selectedCategorie)) {
            $qb->andWhere('p.categorieProduit = :categorie')
               ->setParameter('categorie', $selectedCategorie);
        }

        return $qb->getQuery()->getResult();
    }

    public function decrementStock(Produit $produit, int $quantite): void
    {
        $produit->setQteProduit(max($produit->getQteProduit() - $quantite, 0));
        $this->_em->persist($produit);
        $this->_em->flush();
    }
    public function incrementStock(Produit $produit, int $quantite): void
{
    $produit->setQteProduit($produit->getQteProduit() + $quantite);
    $this->_em->persist($produit);
    $this->_em->flush();
}
public function findBySearchQuery(string $searchTerm)
{
    $qb = $this->createQueryBuilder('p');

    if (!empty($searchTerm)) {
        $qb->andWhere('p.nomProduit LIKE :searchTerm')
           ->setParameter('searchTerm', '%' . $searchTerm . '%');
    }

    if (!empty($categorie)) {
        $qb->andWhere('p.categorie = :categorie')
           ->setParameter('categorie', $categorie);
    }

    return $qb->getQuery();
}

}

 
    //    /**
    //     * @return Produit[] Returns an array of Produit objects
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

    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

