<?php

namespace App\Repository;

use App\Entity\UserTool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserTool>
 *
 * @method UserTool|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTool|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTool[]    findAll()
 * @method UserTool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTool::class);
    }

//    /**
//     * @return UserTool[] Returns an array of UserTool objects
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

//    public function findOneBySomeField($value): ?UserTool
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
