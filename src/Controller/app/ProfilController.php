<?php

namespace App\Controller\app;

use App\Form\EditUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'profil_')]
class ProfilController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em, private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    // ici une déclaration de route, donc l'url ainsi que le nom associé à l'url. Dans la fonction index, on importe la
    // requette qu'on stock dans la variable $request
    #[Route('/show', name: 'show')]
    public function index(Request $request): Response
    {
        //Récupération de l'utilisateur connecté et stockage dans la variable $user
        $user = $this->getUser();
        //Création du formulaire d'édition de l'utilisateur
        $form = $this->createForm(EditUserType::class, $user);

        //Récupération des informations du formulaire dans la requête POST
        $form->handleRequest($request);
        //Vérification de la validité des informations du formulaire et de la soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            //Hash du nouveau mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
            //Rajout du nouveau mot de pass hashé dans l'utilisateur
            $user->setPassword($hashedPassword);
            //Envoie dans la BDD de l'utilisateur modifié
            $this->em->persist($user);
            $this->em->flush();
        }
        //Render de la page avec la clé contenant l'user et celle contenant le formulaire
        return $this->render('profil/index.html.twig', [
            'user'=> $user,
            'form'=> $form,
            'theme' => $request->cookies->get("themeMode")
        ]);
        //Alors, on récupère l'utilisateur connecté et on le stock dans une variable pour pouvoir agir sur l'instance.
        // Ensuite on crée le formulaire et on lui stock les anciennes données de l'user pour préremplir le formulaire.
        //A ce stade, la variable form contient un objet avec toutes les informations du formulaire. On récupère ensuite
        // les informations de la requete post. Nous verifions si le formulaire est soumi et valide, si c'est le cas on
        // passe au traitement des informations. On hash le nouveau mot dze passe et on l'ajoute à notre instance $user.
        // Par la suite on envoie la nouvelle instance modifiée dans la bdd. La derniere ligne permet le render de la page
        // avec 2 clés utilisables dans la vue. C'est la même route qui s'occupe de rendre la page et de gerer le
        // formulaire car justement on rentre dans le traitement du formulaire uniquement au submit.
    }
}
