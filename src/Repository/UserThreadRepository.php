<?php

namespace App\Repository;

use App\Entity\UserThread;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserThread>
 *
 * @method UserThread|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserThread|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserThread[]    findAll()
 * @method UserThread[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserThreadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserThread::class);
    }

//    /**
//     * @return UserThread[] Returns an array of UserThread objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserThread
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
