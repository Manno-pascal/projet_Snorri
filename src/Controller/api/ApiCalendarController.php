<?php

namespace App\Controller\api;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('/api/calendar', name: 'api_calendar') ]
class ApiCalendarController extends AbstractController
{
    #[Route('/get-events', name: '_get_events', options: ['expose' => true], methods: ['GET'])]
    #[OA\Tag(name: 'Calendrier')]
    public function getEvents(EventRepository $eventRepository, Security $security, SerializerInterface $serializer): Response
    {

        return $this->json([
            'data' => $serializer->normalize($eventRepository->findAll(), null, ['groups' => 'event:read']),
        ]);
    }
}
