<?php

namespace App\Controller\api;



use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiErrorCatcherController extends AbstractController
{
    #[Route('/send-error', name: 'send_error', options: ['expose' => true], methods: ['POST'])]
    public function getMessages(Request $request, Security $security,LoggerInterface $logger): Response
    {
        $user = $security->getUser();
        $ticketNumber = uniqid('', true);
        $date = new \DateTime();
        $logger->error($request->getContent(),[
            'date'=>$date,
            'user' => $user->getId(),
            'ticketNumber' => $ticketNumber
        ]);
        return $this->json($ticketNumber);

    }
}