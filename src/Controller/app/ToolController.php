<?php

namespace App\Controller\app;

use App\Entity\Tool;
use App\Enum\StatusEnum;
use App\Form\AddToolType;
use App\Repository\ToolRepository;
use App\Service\UploaderHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tool', name: 'app_tool')]
class ToolController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/index', name: '_index')]
    public function index(ToolRepository $toolRepository, Request $request): Response
    {
        return $this->render('tool/index.html.twig', [
            'categories' => $toolRepository->findCategories(),
            'theme' => $request->cookies->get("themeMode")
        ]);
    }


    #[Route('/add', name: '_add')]
    public function addTool(Request $request, UploaderHandler $uploaderHandler): Response
    {
        $tool = new Tool();
        $form = $this->createForm(AddToolType::class, $tool);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tool->setUserSender($this->getUser());
            $tool->setStatus(StatusEnum::STATUS_NEW->value);
            $this->em->persist($tool);
            $this->em->flush();
            $uploaderHandler->upload($form->get('image')->getData(),$tool,"image");
            return $this->redirectToRoute('app_tool_add', ['hasSubmit' => true], Response::HTTP_SEE_OTHER);
        }
        return $this->render('tool/add.html.twig', [
            'form' => $form,
            'popup' => $request->query->get("hasSubmit"),
            'theme' => $request->cookies->get("themeMode")
        ]);
    }
}




