<?php

namespace App\Controller\api;

use App\Entity\Message;
use App\Entity\Thread;
use App\Entity\User;
use App\Enum\StatusEnum;
use App\Repository\MessageRepository;
use App\Repository\ThreadRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[Route('/api/forum', name: 'api_forum')]
#[OA\Tag(name: 'Fil de discussion')]
class ApiForumController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/get-threads', name: '_threads', options: ['expose' => true], methods: ['GET'])]
    public function getTools(Request $request, ThreadRepository $threadRepository, SerializerInterface $serializer, Security $security): response
    {
        $user = $security->getUser();
        $categories = $threadRepository->findCategories();
        $threadsPerPage = 30;
        $offset = ($request->query->getInt('page', 1) - 1) * $threadsPerPage;
        if ($category = $request->get('category')) {
            if (in_array($category, $categories)) {
                $threads = $threadRepository->findByCategory($category, $threadsPerPage, $offset);
            } else if ($category === "favorites") {
                $threads = $threadRepository->getFavorites($user, $threadsPerPage, $offset);
            }
        } else if ($keyword = $request->get('keyword')) {
            $threads = $threadRepository->findByKeyword($keyword, $threadsPerPage, $offset);
        }

        if (!$threads){
            throw $this->createNotFoundException('Requête incorrecte');
        }
        $data = [
            'threads' => $serializer->normalize($threads['paginatedThreads'], null, ['groups' => 'thread:read']),
            'pagesNumber' => ceil($threads['totalCount'] / $threadsPerPage)
        ];
        return $this->json($data);
    }

    #[Route('/thread/{id}/messages', name: '_thread_messages', options: ['expose' => true], methods: ['GET'])]
    public function getMessages(Request $request, Thread $thread, ThreadRepository $threadRepository,MessageRepository $messageRepository ,SerializerInterface $serializer): Response
    {
        $messagesPerPage = 20;
        $offset = ($request->query->getInt('page', 1) - 1) * $messagesPerPage;
        $messages = $messageRepository->findByThreads($thread, $messagesPerPage, $offset);
        $data = [
            'messages' => $serializer->normalize($messages['paginatedMessages'], null, ['groups' => 'message:read']),
            'pagesNumber' => ceil($messages['totalCount'] / $messagesPerPage)
        ];
        return $this->json($data);

    }

    #[Route('/thread/{id}/add-message', name: '_add_message', options: ['expose' => true], methods: ['POST'])]
    public function addMessage(Request $request,Security $security, Thread $thread, SerializerInterface $serializer, MessageRepository $messageRepository ): Response
    {
        $user = $security->getUser();
        $message = new Message();
        $message->setUserCreator($user);
        $message->setThread($thread);
        $message->setContent(json_decode($request->getContent(), false));
        $message->setStatus(StatusEnum::STATUS_VALIDATED->value);
        $this->em->persist($message);
        $this->em->flush();
        $messagesPerPage = 20;
        $offset = ($request->query->getInt('page', 1) - 1) * $messagesPerPage;
        $messages = $messageRepository->findByThreads($thread, $messagesPerPage, $offset);
        $data = [
            'messages' => $serializer->normalize($messages['paginatedMessages'], null, ['groups' => 'message:read']),
            'pagesNumber' => ceil($messages['totalCount'] / $messagesPerPage)
        ];
        return $this->json($data);

    }

}
