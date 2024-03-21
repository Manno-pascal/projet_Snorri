<?php

namespace App\Controller\api;

use OpenApi\Attributes as OA;
use App\Service\UploaderHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/profil', name: 'api_profil_')]
#[OA\Tag(name: 'Utilisateur')]
class ApiProfilController extends AbstractController
{
    #[Route('/upload-document', name: 'upload_document', options: ['expose' => true], methods: ['POST'])]
    public function upload(Request $request, EntityManagerInterface $entityManager, UploaderHandler $uploaderHandler): Response
    {
        $entity = $entityManager->getRepository($request->query->get("entityClass"));
        $instance = $entity->find($request->query->get("entityId"));
        $file = $request->files->get('file');
        $uploadedFile = $uploaderHandler->upload($file,$instance,$request->query->get("entityColumnName"));
        return $this->json([
            'url' => $uploadedFile,
        ]);
    }
}
