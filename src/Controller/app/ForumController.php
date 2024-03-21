<?php

namespace App\Controller\app;

use App\Entity\Message;
use App\Entity\Thread;
use App\Enum\StatusEnum;
use App\Form\ThreadMessageType;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forum', name: 'app_forum')]
class ForumController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em, private readonly PaginatorInterface $paginator)
    {
    }

    #[Route('/index', name: '_index')]
    public function index(ThreadRepository $threadRepository, Request $request): Response
    {
        return $this->render('forum/index.html.twig', [
            'categories' => $threadRepository->findCategories(),
            'theme' => $request->cookies->get("themeMode")
        ]);
    }


    #[Route('/add-thread', name: '_add_thread')]
    public function addThread(Request $request): Response
    {
        $threadMessage = new Message();
        $thread = new Thread();
        $form = $this->createForm(ThreadMessageType::class, $threadMessage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $threadMessage->setUserCreator($this->getUser());
            $threadMessage->setStatus(StatusEnum::STATUS_VALIDATED->value);
            $thread->setTitle($form->getData()->getThread()->getTitle());
            $thread->setCategory($form->getData()->getThread()->getCategory());
            $thread->setStatus(StatusEnum::STATUS_VALIDATED->value);
            $thread->setUserCreator($this->getUser());
            $threadMessage->setThread($thread);
            $this->em->persist($threadMessage);
            $this->em->flush();
            return $this->redirectToRoute('app_forum_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forum/add_thread.html.twig', [
            'form' => $form,
            'theme' => $request->cookies->get("themeMode")
        ]);
    }


    #[Route('/thread', name: '_thread', options: ['expose' => true])]
    public function thread(Request $request, ThreadRepository $threadRepository): Response
    {
        $thread = $threadRepository->find($request->query->get("thread"));

        return $this->render('forum/thread.html.twig', [
            'thread' => $thread,
            'theme' => $request->cookies->get("themeMode")
        ]);
    }
}
