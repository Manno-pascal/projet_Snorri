<?php

namespace App\Repository;

use App\Entity\Offer;
use App\Enum\StatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
// ici se trouve le dql ainsi que les fonction basiques comme le find, findOneBy findAll et findBy
//le find permet de rédcupérer une instance grace à l'id
//le findOneBy permet de récupérer la premiere instance correspondant aux criteres qu'on lui passe pour les values des colonnes
//le findAll permet de retourner l'ensemble des instances de l'entité
//le findBy permet de retourner l'ensemble des instances correpondants aux criteres qu'on lui passe d'une entité
/**
 * @extends ServiceEntityRepository<Offer>
 *
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    //ici la déclaration d'une fonction dql, celle pour filtrer les offres. on lui passe des parametres, ceux pour la pagination
    // ainsi que les filtres. pour les filtres, si aucune valeur n'est passée, lui la définie par défaut sur null.
    // on type la réponse, elle sera du type tableau
    public function findByFilters($offersPerPage, $offset, $location = null, $contractType = null, $keyword = null): array
    {
//on définie une requette dql, 'o' est un alias pour Offer, désormais lorsqu'on veut faire référence à la table Offer, on
        // l'appellera par l'alias 'o'
        $qb = $this->createQueryBuilder('o');
        //on vérifie quel est le type de filtre qu'on passe et on execute le code en fonction.
        if ($location) {
            //si c'est ce filtre qui est passé, on recherchera sur la colonne location la valeur contenue dans la variable
            // le setParameter permet de rajouter un parametre dans la requete, on passe par le setParameter afin d'éviter
            // l'injection sql, le pourcent avant et apres la variable permet une recherche partielle, c'est a dire
            // de recherche toute correspondance ou il y a du texte avant et apres la variable
            // exemple : recherche = 'sei' => correspondances = 'mar.sei.lle', 'Sei.ne-et-Marne' ...
            // le andwhere permet de recupérer donc les instances qui contiennent ce parametre
            $qb->andWhere('o.location LIKE :location')
                ->setParameter('location', '%' . $location . '%');
        }
        if ($contractType) {
            $qb->andWhere('o.contract_type = :contractType')
                ->setParameter('contractType', $contractType);
        }
        if ($keyword) {
            //ici on part du principe que les utilisateurs peuvent entrer des phrases, on va donc chercher à creer un tableau
            // ou chaque valeur est un des mots contenu dans le tableau :
            //Exemple : "Je suis petit" = > ["je","suis","petit"]

            $keywords = explode(' ', $keyword);
            $formatedKeywords = [];
            for ($i = 0; $i < count($keywords); $i++) {
                if (strlen($keywords[$i]) > 2) {
                    $formatedKeywords[] = $keywords[$i];
                }
            }
            $conditions = [];
            foreach ($formatedKeywords as $word) {
                // on parcourt le tableau contenants les mots et pour chaque mots, on recherche des instances qui contiennent
                // soit dans le titre soit dans la description le mot
                $conditions[] = 'o.title LIKE :keyword OR o.description LIKE :keyword';
                $qb->setParameter('keyword', '%' . $word . '%')
                    ->andWhere(implode(' OR ', $conditions));
            }
        }
//on récupère uniquement les instances avec le status validé
        $paginatedOffers = $qb->andWhere('o.status = :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            //on range en fonction de la date
            ->orderBy('o.date', 'ASC')
            //on applique la pagination : firstResult = la valeur à partir de laquelle on commence à récupérer
                //on récupère les valeurs à partir de la 10eme valeur
            ->setFirstResult($offset)
            // on fixe un nombre d'instance qu'on récupère
            ->setMaxResults($offersPerPage)
            //on execute la requette
            ->getQuery()
            ->getResult();

//ici on réexecute la même requete mais sansl a pagination afin de récupérer le nombre total de page pour la pagination
        $qb = $this->createQueryBuilder('o');
        if ($location) {
            $qb->andWhere('o.location LIKE :location')
                ->setParameter('location', '%' . $location . '%');
        }
        if ($contractType) {
            $qb->andWhere('o.contract_type = :contractType')
                ->setParameter('contractType', $contractType);
        }
        if ($keyword) {
            $keywords = explode(' ', $keyword);
            $formatedKeywords = [];
            for ($i = 0; $i < count($keywords); $i++) {
                if (strlen($keywords[$i]) > 2) {
                    $formatedKeywords[] = $keywords[$i];
                }
            }
            $conditions = [];
            foreach ($formatedKeywords as $word) {
                $conditions[] = 'o.title LIKE :keyword OR o.description LIKE :keyword';
                $qb->setParameter('keyword', '%' . $word . '%')
                    ->andWhere(implode(' OR ', $conditions));
            }
        }
        $totalCount =
            $qb->select('COUNT(o.id)')
            ->andWhere('o.status LIKE :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            ->getQuery()
            ->getSingleScalarResult();
// on retourne les résultats
        return [
            'paginatedOffers' => $paginatedOffers,
            'totalCount' => $totalCount,
        ];
    }
//une fonction pour récupérer l'ensemble des offres validées sans filtre sauf qu'on applique la pagination
    public function findByValidated($offersPerPage, $offset): array
    {
        $paginatedOffers = $this->createQueryBuilder('o')
            ->andWhere('o.status LIKE :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            ->orderBy('o.date', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($offersPerPage)
            ->getQuery()
            ->getResult();

        $totalCount = $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->andWhere('o.status LIKE :status')
            ->setParameter('status', StatusEnum::STATUS_VALIDATED->value)
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'paginatedOffers' => $paginatedOffers,
            'totalCount' => $totalCount,
        ];
    }


//    /**
//     * @return Offer[] Returns an array of Offer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offer
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
