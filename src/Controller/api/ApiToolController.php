<?php

namespace App\Controller\api;

use App\Entity\User;
use App\Entity\UserTool;
use App\Repository\ToolRepository;
use App\Repository\UserToolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[Route('/api/tool', name: 'api_tool')]
#[OA\Tag(name: 'Outil')]
class ApiToolController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/edit-favorite', name: '_edit_favorite', options: ['expose' => true], methods: ['POST'])]
    public function editFavoriteTool(Request $request, Security $security, ToolRepository $toolRepository, UserToolRepository $userToolRepository): response
    {
        $user = $security->getUser();
        $isFavorite = $userToolRepository->findOneBy(['user' => $user->getId(), 'tool' => $request->query->get("tool")]);
        if ($isFavorite) {
            $user->removeUserTool($isFavorite);
            $isAddition = false;
        } else {
            $favorite = new UserTool();
            $favorite->setTool($toolRepository->find($request->query->get("tool")));
            $favorite->setUser($user);
            $this->em->persist($favorite);
            $isAddition = true;
        }
        $this->em->flush();
        return $this->json($isAddition);
    }



    #[Route('/get-tools', name: '_tools', options: ['expose' => true], methods: ['GET'])]
    public function getTools(Request $request,Security $security, ToolRepository $toolRepository, SerializerInterface $serializer): response
    {
        $user = $security->getUser();
        $technologies = $toolRepository->findCategories();
        $toolsPerPage = 8;
        $offset = ($request->query->getInt('page', 1) - 1) * $toolsPerPage;
        if ($category = $request->get('category')) {
            if (in_array($category, $technologies)) {
                $tools = $toolRepository->findByCategory($category, $toolsPerPage, $offset);
            } else if ($category === "favorites"){
                $tools = $toolRepository->getFavorites($user, $toolsPerPage, $offset);
            }

        }else if ($keyword = $request->get('keyword')){
            $tools = $toolRepository->findByKeyword($keyword, $toolsPerPage, $offset);
        }
        if (!$tools){
            throw $this->createNotFoundException('Requête incorrecte');
        }
        $data =[
            'tools' => $serializer->normalize($tools['paginatedTools'], null, ['groups' => 'tool:read']),
            'pagesNumber' => ceil($tools['totalCount']/$toolsPerPage)
        ];
        return $this->json($data);
    }
}




