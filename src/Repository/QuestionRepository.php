<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findAllQuestions(): array
    {
        return $this->createQueryBuilder('q')
            ->getQuery()
            ->getResult();
    }
    
    public function searchQuestions(?string $text, ?string $type): array
{
    $qb = $this->createQueryBuilder('q');

    if ($text) {
        $qb->andWhere('q.questionText LIKE :text')
           ->setParameter('text', "%$text%");
    }

    if ($type) {
        $qb->andWhere('q.answerType = :type')
           ->setParameter('type', $type);
    }

    return $qb->getQuery()->getResult();
}


//    /**
//     * @return Question[] Returns an array of Question objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Question
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
