<?php

namespace App\Repository;

use App\Entity\Centre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Centre>
 */
class CentreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Centre::class);
    }

    /**
     * Recherche et tri des centres.
     *
     * @param string|null $searchTerm Terme de recherche
     * @param string $sortBy Champ de tri (par défaut : 'nomCentre')
     * @param string $sortOrder Ordre de tri (par défaut : 'ASC')
     * @return \Doctrine\ORM\Query
     */
    public function searchAndSort($searchTerm = null, $sortBy = 'nomCentre', $sortOrder = 'ASC')
    {
        $qb = $this->createQueryBuilder('c');

        // Appliquer le filtre de recherche si un terme est fourni
        if ($searchTerm) {
            $qb->andWhere('c.nomCentre LIKE :searchTerm OR c.adresseCentre LIKE :searchTerm OR c.emailCentre LIKE :searchTerm')
               ->setParameter('searchTerm', '%' . $searchTerm . '%');
        }

        $qb->orderBy("c.$sortBy", $sortOrder);

        return $qb->getQuery();
    }

    // Exemples de méthodes existantes (commentées)
    // /**
    //  * @return Centre[] Returns an array of Centre objects
    //  */
    // public function findByExampleField($value): array
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('c.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // public function findOneBySomeField($value): ?Centre
    // {
    //     return $this->createQueryBuilder('c')
    //         ->andWhere('c.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->getQuery()
    //         ->getOneOrNullResult()
    //     ;
    // }
}