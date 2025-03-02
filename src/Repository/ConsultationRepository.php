<?php

namespace App\Repository;

use App\Entity\Consultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Consultation>
 */
class ConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consultation::class);
    }


    public function findBySearchQueryAndSort(string $searchQuery, string $nomFilter, string $prenomFilter, string $ageFilter, string $sortBy, string $order)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.nom LIKE :nom')
            ->andWhere('c.prenom LIKE :prenom')
            ->andWhere('c.age LIKE :age')
            ->setParameter('nom', '%'.$searchQuery.'%')
            ->setParameter('prenom', '%'.$searchQuery.'%')
            ->setParameter('age', '%'.$searchQuery.'%')
            ->orderBy('c.' . $sortBy, $order);
    
        if ($nomFilter) {
            $qb->andWhere('c.nom LIKE :nomFilter')
               ->setParameter('nomFilter', '%'.$nomFilter.'%');
        }
    
        if ($prenomFilter) {
            $qb->andWhere('c.prenom LIKE :prenomFilter')
               ->setParameter('prenomFilter', '%'.$prenomFilter.'%');
        }
    
        if ($ageFilter) {
            $qb->andWhere('c.age LIKE :ageFilter')
               ->setParameter('ageFilter', '%'.$ageFilter.'%');
        }
    
        return $qb->getQuery()->getResult();
    }
    
    


//    /**
//     * @return Consultation[] Returns an array of Consultation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Consultation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
