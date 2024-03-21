<?php

namespace App\Controller\app;

use App\Entity\Offer;
use App\Enum\ContractTypeEnum;
use App\Enum\StatusEnum;
use App\Form\AddOfferType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//Déclaration du prefix des routes pour les offres d'emploi
#[Route('/offer', name: 'app_offer')]
class OfferController extends AbstractController
{
    //La fonction construct permet d'injecter des dépendances nécéssaire dans l'ensemble des routes.
    // Les dépendances seront accessible en plaçant le '$this' devant ces dernières pour y accéder (principe du POO)
    //exemple : $this->em->...
    private $user;

    public function __construct(
        //On injecte ici le entityManagerInterface, le private signifie qu'on pourra utiliser cette dépendance dans
        //dans cette classe uniquement.
        private readonly EntityManagerInterface $em,
    )
    {
    }


    //Déclaration de la route index, on lui spécifie l'url ainsi que le nom. Dans symfony, l'appel d'une route se fait
    //uniquement pas le nom et pas par l'url
    #[Route('/index', name: '_index')]
    //On injecte là aussi des dépendances afin de les utiliser dans la logique. On type la return de la réponse, à
    // l'occurence, il s'agit ici d'un return de type response
    public function index(Request $request): Response
    {
        //On retourne simplement la vue twig ainsi que deux clés : theme permettant de récupèrer le cookie et l'envoyer
        // à la vue pour appliquer selon les préférences de l'utilise le dark mode ou le light mode ainsi que la liste
        // des types de contrat pour les filtres *voir src/Enum/CategoryEnum.php*
        return $this->render('offer/index.html.twig', [
            'theme' => $request->cookies->get("themeMode"),
            'contractList' => array_flip(ContractTypeEnum::getTranslatedContractsList())
        ]);
    }


    //Déclaration de la route d'ajout des offres
    #[Route('/add-offer', name: '_add_offer')]
    //On injecte là aussi des dépendances afin de les utiliser dans la logique. On type la return de la réponse, à
        // l'occurence, il s'agit ici d'un return de type response
    public function addOffer(Request $request): Response
    {
        // Création d'une instance d'offer, correspond à une nouvelle offre
        $offer = new Offer();
        // On génére le formulaire à partir d'une form type "AddOfferType", on le stock dans la variable $form
        // On lui passe l'instance d'offer afin de stocker les valeurs entrées par l'utilisateur lors de la soumission
        // du formulaire.
        $form = $this->createForm(AddOfferType::class, $offer);
        // On écoutes les requetes afin de récupérer les données relatives au formulaire (pour détecter la soumission)
        $form->handleRequest($request);
        // On vérie si le formulaire a été soumi et si il est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On ajoute en tant que créateur, l'utilisateur qui à envoyer le formulaire
            $offer->setUserCreator($this->getUser());
            // On défini le statut new des que l'offre est soumise afin qu'elle soit vérifiée par un admin
            // La liste des status est contenue dans le StatusEnum *Voir le src/Enum/StatusEnum.php*
            $offer->setStatus(StatusEnum::STATUS_NEW->value);
            $this->em->persist($offer);
            $this->em->flush();
            //On redirige vers une route en get pour eviter de re soumettre le formulaire en cas de refresh
            return $this->redirectToRoute('app_offer_add_offer', ['hasSubmit' => true], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offer/add_offer.html.twig', [
            'form' => $form,
            'popup' => $request->query->get("hasSubmit"),
            'theme' => $request->cookies->get("themeMode")
        ]);
    }

    // déclarations la route pour acceder a la gestion des offres déposées par les utilisateurs//
    // On déclare la route ainsi que le nom de la route
    #[Route('/offer-management', name: '_management')]
    //On importe les injections pour pouvoir les utiliser dans le code plus tard
    public function offerManagement(OfferRepository $offerRepository, Request $request): Response
    {
        //on récupère l'instance de l'utilisateur connecté
        $user = $this->getUser();
        //on récupère les offres créées par le user, elles sont stockées dans une variable
        $offers = $offerRepository->findBy(['user_creator' => $user->getId()]);
        //on rend la view twig et on envoie également les offres
        return $this->render('offer/display_offers_management.html.twig', [
            'offers' => $offers,
            'theme' => $request->cookies->get("themeMode"),
        ]);
    }
// Déclaration de la route pour afficher une offre en particulier
//  on défini l'url ainsi que la nom de la route
    #[Route('/offer-management/show/{id}', name: '_management_show')]
    //là encore nous récupérer l'instance de offer grace au paramconverter et à l'id contenue dans l'url
    //On défini le nom de la fonction et on injecte les class que nous avons besoin dans notre traitement
    public function offerManagementShow(Offer $offer, Request $request): Response
    {
        $this->user = $this->getUser();
        if ($offer->getUserCreator() != $this->user){
            throw $this->createAccessDeniedException("Cette offre ne vous est pas accessible");
      }
//on crée le formulaire d'edition des offres, on lui donne l'instance de l'offre existance afin de preremplir les champs
        $form = $this->createForm(AddOfferType::class, $offer);
        //Lorsque le formulaire est soumi, on récupère la requete pour récupérer les données du formulaire
        $form->handleRequest($request);
        //On vérifie si le formulaire est soumi et les champs sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            //On reset le status sur new afin de faire valider les informations
            $offer->setStatus(StatusEnum::STATUS_NEW->value);
            $this->em->persist($offer);
            $this->em->flush();
            //On redirige vers la même route afin de retourner en GET
            return $this->redirectToRoute('app_offer_management_show', ['id' => $offer->getId(), Response::HTTP_SEE_OTHER]);
        }
        //On rend le template twig avec les informations nécéssaires pour build le front, le formulaire généré plus haut
        // l'offre afin de l'afficher en front et le theme
        return $this->render('offer/show_offer_management.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
            'theme' => $request->cookies->get("themeMode"),
        ]);
    }
// Déclaration de la route pour delete une offre en particulier
//  on défini l'url ainsi que la nom de la route
    #[Route('/offer-management/delete/{id}', name: '_management_delete')]
    //On récupère l'instance de offer grace à l'id et au paramconverter
    public function offerManagementDelete(Offer $offer): Response
    {
        $this->user = $this->getUser();
        if ($offer->getUserCreator() != $this->user){
            throw $this->createAccessDeniedException("Cette offre ne vous est pas accessible");
        }
        //on remove notre instance
        $this->em->remove($offer);
        //on sauvegarde la modification en bdd
        $this->em->flush();
        //on redirige vers la liste des offres
        return $this->redirectToRoute('app_offer_management', [Response::HTTP_SEE_OTHER]);
    }
}

