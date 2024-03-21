<?php

namespace App\Controller\api;

use App\Entity\Offer;
use App\Entity\User;
use App\Entity\UserTool;
use Nelmio\ApiDocBundle\Model\Model;
use OpenApi\Attributes as OA;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyInfo\Type;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

//Déclaration du prefix des routes pour les offres d'emploi
#[Route('/api/offer', name: 'api_offer')]
#[OA\Tag(name: "Offre d'emploi")]
class ApiOfferController extends AbstractController
{
    //La fonction construct permet d'injecter des dépendances nécéssaire dans l'ensemble des routes.
    // Les dépendances seront accessible en plaçant le '$this' devant ces dernières pour y accéder (principe du POO)
    //exemple : $this->em->...
    public function __construct(
        //On injecte ici le entityManagerInterface, le private signifie qu'on pourra utiliser cette dépendance dans
        //cette classe uniquement
        private readonly EntityManagerInterface $em,
    )
    {
    }


    // déclaration de la route get offers, une route d'api pour afficher les outils de façon
    // dynamique *voir assets/controllers/offer_controller.js*, le expose => true permet d'utiliser cette route avec
    // fosrouter, un package permettant d'utiliser les routes dans le javascript grâce au nom de la route
    #[Route('/get-offers', name: '_get_offers', options: ['expose' => true], methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
    )]
    #[OA\Parameter(
        name: 'contract',
        description: 'Entrez le type de contrat recherché',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: "location",
        description: "Entrez l'emplacement de contrat recherché",
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'keyword',
        description: 'Entrez un mot clé',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'page',
        description: 'Entrez le numéro de la page',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]


    //On injecte là aussi des dépendances afin de les utiliser dans la logique. On type la return de la réponse, à
        // l'occurence, il s'agit ici d'un return de type response
    public function getOffers(Request $request, OfferRepository $offerRepository, SerializerInterface $serializer): response
    {
        // on récupère les queries transmisent par le javascript
        // (https://localhost:8000/offer/get-offers/?contract=CDD&location=Marseille&keyword=PHP) par exemple
        $contract = $request->query->get('contract');
        $location = $request->query->get('location');
        $keyword = $request->query->get('keyword');
        //On défini le nombre d'offre par page
        $offersPerPage = 10;
        //on calcule le offset, c'est à dire le décalage dans la bdd des offres pour la pagination
        // exemple Page 3 on récupère 10 offres à partir de la 20eme offre dans la bdd
        // page 1 = 1->10 page 2 = 11->20 page 3 = 21->30
        $offset = ($request->query->getInt('page', 1) - 1) * $offersPerPage;
        // Si un filtre est passé en querry par l'utilisateur, on le récupère et on applique le filtre dans le dql
        // pour retourner les résultats filtrés voir *src/Repository/OfferRepository.php*
        if ($contract || $location || $keyword) {
            //Ici on appelle la fonction permettant de retourner les résultats filtrés avec la pagination
            $offers = $offerRepository->findByFilters($offersPerPage, $offset, $location, $contract, $keyword);
        } else {
            //si aucun filtre n'est passé on retourne simplement les résultats sans filtres mais avec la pagination quand
            // même
            $offers = $offerRepository->findByValidated($offersPerPage, $offset);
        }
        //On prépare la réponse, on normalize les données afin d'utiliser les groupes spécifier dans les entités.
        // voir *src/Entity/Offer.php*
        // La normalization permet de retourner uniquement les colonnes souhaitées de l'entité.
        // Cela permet également d'éviter les erreur de circulaire
        // (une instance b qui fait réference à une instance a qui fait elle même réference à l'instance b qui fait elle
        // même réference à l'entité a et ainsi desuite jusqu'à l'infini provoquant donc une boucle infinie)
        //On passe également le nombre de page total sans la pagination afin de mettre à jour le nombre de page dans la
        // pagination coté front

        if (!$offers){
            throw $this->createNotFoundException('Requête incorrecte');
        }
        $data = [
            'offers' => $serializer->normalize($offers['paginatedOffers'], null, ['groups' => 'offer:read']),
            'pagesNumber' => ceil($offers['totalCount'] / $offersPerPage)
        ];
        //On retourne les données
        return $this->json($data);
    }


    // déclaration de la route get offer, une route d'api pour afficher un outil spécifique de façon
    // dynamique *voir assets/controllers/offer_controller.js*, le expose => true permet d'utiliser cette route avec
    // fosrouter, un package permettant d'utiliser les routes dans le javascript grâce au nom de la route
    #[Route('/get-offer/{id}', name: '_get_offer', options: ['expose' => true], methods: ['GET'])]
    // L'id dans l'url envoyé par l'utilisateur est transformé en instance de l'entité offer contenue dans les injections
        // grâce au ParamConverteur de symfony. Il s'occupe de récupérer l'id et de faire un find sur l'entité pour retrouver
        // l'instance correspondante à cet id
    public function getOffer(Offer $offer, SerializerInterface $serializer): response
    {
        //on retourne les données de l'outil, là encore normalisées afin de retourner uniquement les colonnes de données
        // souhaitées
        if (!$offer){
            throw $this->createNotFoundException('Requête incorrecte');
        }
        $data = $serializer->normalize($offer, null, ['groups' => 'offer:read']);
        return $this->json($data);
    }
}

