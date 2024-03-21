<?php

namespace App\Repository;

use App\Entity\Message;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }
    public function findByThreads($thread, $toolsPerPage, $offset): array
    {
        $paginatedMessages = $this->createQueryBuilder('m')
            ->andWhere('m.thread = :thread')
            ->setParameter('thread', $thread)
            ->andWhere('m.status = :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            ->orderBy('m.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($toolsPerPage)
            ->getQuery()
            ->getResult();

        $totalCount = $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->andWhere('m.status = :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            ->andWhere('m.thread = :thread')
            ->setParameter('thread', $thread)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'paginatedMessages' => $paginatedMessages,
            'totalCount' => $totalCount,
        ];
    }

//    /**
//     * @return Message[] Returns an array of Message objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
