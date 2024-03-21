<?php

namespace App\Controller\app;

use App\Repository\InformationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home', options: ['expose' => true])]
    public function index(InformationRepository $informationRepository, Request $request): Response
    {
        return $this->render('home/index.html.twig', [
            'informations' => $informationRepository->findAll(),
            'theme' => $request->cookies->get("themeMode")
        ]);
    }
}
