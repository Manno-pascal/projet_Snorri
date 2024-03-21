<?php

namespace App\Repository;

use App\Entity\Thread;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Thread>
 *
 * @method Thread|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thread|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thread[]    findAll()
 * @method Thread[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThreadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thread::class);
    }

    public function findCategories(): array
    {
        $categories = $this->createQueryBuilder('t')
            ->select('t.category as category')
            ->andWhere('t.status LIKE \'' . StatusEnum::STATUS_VALIDATED->value . '\'')
            ->orderBy('category', 'ASC')
            ->groupBy('category')
            ->getQuery()
            ->getResult();

        $choices = [];

        foreach ($categories as $category) {
            $choices[$category["category"]] = $category["category"];
        }
        return $choices;
    }

    public function findByKeyword($keyword, $threadsPerPage, $offset): array
    {
        $keywords = explode(' ', $keyword);

        $formatedKeywords = [];
        foreach ($keywords as $word) {
            if (strlen($word) > 2) {
                $formatedKeywords[] = $word;
            }
        }

        $qb = $this->createQueryBuilder('t');
        $conditions = [];

        foreach ($formatedKeywords as $word) {
            $conditions[] = 't.title LIKE :keyword';
            $qb->setParameter('keyword', '%' . $word . '%');
        }


        $paginatedThreads =
            $qb->andWhere(implode(' OR ', $conditions))
                ->andWhere('t.status LIKE :status')
                ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
                ->setFirstResult($offset)
                ->setMaxResults($threadsPerPage)
                ->getQuery()
                ->getResult();

        $qb = $this->createQueryBuilder('t');
        foreach ($formatedKeywords as $word) {
            $conditions[] = 't.title LIKE :keyword';
            $qb->setParameter('keyword', '%' . $word . '%');
        }
        $totalCount =
            $qb->select('COUNT(t.id)')
                ->andWhere(implode(' OR ', $conditions))
                ->andWhere('t.status LIKE :status')
                ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
                ->getQuery()
                ->getSingleScalarResult();

        return [
            'paginatedThreads' => $paginatedThreads,
            'totalCount' => $totalCount,
        ];
    }

    public function getFavorites($user, $threadsPerPage, $offset): array
    {
        $paginatedThreads = $this->createQueryBuilder('t')
            ->leftJoin('t.userThreads', 'ut')
            ->leftJoin('ut.user', 'u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user->getId())
            ->orderBy('t.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($threadsPerPage)
            ->getQuery()
            ->getResult();

        $totalCount = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->leftJoin('t.userThreads', 'ut')
            ->leftJoin('ut.user', 'u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'paginatedThreads' => $paginatedThreads,
            'totalCount' => $totalCount,
        ];
    }

    public function findByCategory($category, $threadsPerPage, $offset): array
    {
        $paginatedThreads = $this->createQueryBuilder('t')
            ->andWhere('t.category = :category')
            ->setParameter('category', $category)
            ->andWhere('t.status LIKE :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            ->orderBy('t.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($threadsPerPage)
            ->getQuery()
            ->getResult();

        $totalCount = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.status LIKE :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            ->andWhere('t.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'paginatedThreads' => $paginatedThreads,
            'totalCount' => $totalCount,
        ];
    }



//    /**
//     * @return Thread[] Returns an array of Thread objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Thread
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
