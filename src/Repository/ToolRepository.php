<?php

namespace App\Repository;

use App\Entity\Tool;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tool>
 *
 * @method Tool|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tool|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tool[]    findAll()
 * @method Tool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tool::class);
    }

    public function findCategories(): array
    {
        $categories = $this->createQueryBuilder('t')
            ->select('t.category as category')
            ->andWhere('t.status LIKE  :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
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

    public function findByKeyword($keyword, $toolsPerPage, $offset): array
    {

        $keywords = explode(' ', $keyword);

        $formatedKeywords = [];
        for ($i = 0; $i < count($keywords); $i++) {
            if (strlen($keywords[$i]) > 2) {
                $formatedKeywords[] = $keywords[$i];
            }
        }

        $qb = $this->createQueryBuilder('t');
        $conditions = [];


        foreach ($formatedKeywords as $word) {
            $conditions[] = 't.title LIKE :keyword OR t.description LIKE :keyword';
            $qb->setParameter('keyword', '%' . $word . '%');
        }
        $paginatedTools =
            $qb->andWhere(implode(' OR ', $conditions))
                ->andWhere('t.status LIKE :status')
                ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
                ->setFirstResult($offset)
                ->setMaxResults($toolsPerPage)
                ->getQuery()
                ->getResult();


        $qb = $this->createQueryBuilder('t');
        foreach ($formatedKeywords as $word) {
            $conditions[] = 't.title LIKE :keyword OR t.description LIKE :keyword';
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
            'paginatedTools' => $paginatedTools,
            'totalCount' => $totalCount,
        ];
    }

    public function getFavorites($user, $toolsPerPage, $offset): array
    {

        $paginatedTools = $this->createQueryBuilder('t')
            ->leftJoin('t.userTools', 'ut')
            ->leftJoin('ut.user', 'u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user->getId())
            ->andWhere('t.status LIKE :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            ->orderBy('t.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($toolsPerPage)
            ->getQuery()
            ->getResult();

        $totalCount = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->leftJoin('t.userTools', 'ut')
            ->leftJoin('ut.user', 'u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user->getId())
            ->andWhere('t.status LIKE :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'paginatedTools' => $paginatedTools,
            'totalCount' => $totalCount,
        ];

    }

    public function findByCategory($category, $toolsPerPage, $offset): array
    {
        $paginatedTools = $this->createQueryBuilder('t')
            ->andWhere('t.category = :category')
            ->setParameter('category', $category)
            ->andWhere('t.status LIKE :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            ->orderBy('t.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($toolsPerPage)
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
            'paginatedTools' => $paginatedTools,
            'totalCount' => $totalCount,
        ];
    }




//    /**
//     * @return Tool[] Returns an array of Tool objects
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

//    public function findOneBySomeField($value): ?Tool
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
