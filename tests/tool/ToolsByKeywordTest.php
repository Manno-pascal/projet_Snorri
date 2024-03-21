<?php

namespace App\Tests\tool;

use App\Entity\Tool;
use App\Entity\User;
use App\Enum\CategoryEnum;
use App\Enum\StatusEnum;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ToolsByKeywordTest extends KernelTestCase
{
    public function testFindByKeyword(): void
    {
        $kernel = self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $repository = $entityManager->getRepository(Tool::class);
        $firstTool = new Tool();
        $secondTool = new Tool();
        $thirdTool = new Tool();
        $user = new User();

        $user->setEmail("john-doe@gmail.com");
        $user->setPassword("azerty");
        $user->setLastname("doe");
        $user->setFirstname("john");
        $user->setAddress("23 rue de l'église");
        $user->setStatus(StatusEnum::STATUS_VALIDATED->value);

        $firstTool->setStatus(StatusEnum::STATUS_VALIDATED->value);
        $firstTool->setTitle('Github');
        $firstTool->setImage('Github');
        $firstTool->setDescription("GitHub est un service web d'hébergement et de gestion de développement de logiciels, utilisant le logiciel de gestion de versions Git. Ce site est développé en Ruby on Rails et Erlang par Chris Wanstrath, PJ Hyett et Tom Preston-Werner. GitHub propose des comptes professionnels payants, ainsi que des comptes gratuits pour les projets de logiciels libres.");
        $firstTool->setUserSender($user);
        $firstTool->setImage('/assets/uploads/tools/github.png');
        $firstTool->setCategory(CategoryEnum::CATEGORY_OTHER->value);
        $firstTool->setUrl('https://github.com');

        $secondTool->setStatus(StatusEnum::STATUS_VALIDATED->value);
        $secondTool->setTitle('Github without keyword');
        $secondTool->setDescription("GitHub est un service web d'hébergement et de gestion de développement de code, utilisant le site de gestion de versions Git. Ce site est développé en Ruby on Rails et Erlang par Chris Wanstrath, PJ Hyett et Tom Preston-Werner. GitHub propose des comptes professionnels payants, ainsi que des comptes gratuits pour les projets de développements libres.");
        $secondTool->setUserSender($user);
        $secondTool->setImage('/assets/uploads/tools/github.png');
        $secondTool->setCategory(CategoryEnum::CATEGORY_OTHER->value);
        $secondTool->setUrl('https://github.com');

        $thirdTool->setStatus(StatusEnum::STATUS_DISABLED->value);
        $thirdTool->setTitle('Github disabled');
        $thirdTool->setDescription("GitHub est un service web d'hébergement et de gestion de développement de logiciels, utilisant le logiciel de gestion de versions Git. Ce site est développé en Ruby on Rails et Erlang par Chris Wanstrath, PJ Hyett et Tom Preston-Werner. GitHub propose des comptes professionnels payants, ainsi que des comptes gratuits pour les projets de logiciels libres.");
        $thirdTool->setUserSender($user);
        $thirdTool->setImage('/assets/uploads/tools/github.png');
        $thirdTool->setCategory(CategoryEnum::CATEGORY_OTHER->value);
        $thirdTool->setUrl('https://github.com');

        $entityManager->persist($user);
        $entityManager->persist($firstTool);
        $entityManager->persist($secondTool);
        $entityManager->persist($thirdTool);
        $entityManager->flush();

        $results = $repository->findByKeyword("logiciel",8,0);

        $response = [
            'paginatedTools'=>[$firstTool],
            "totalCount" => 1
        ];
        

        $this->assertSame($response, $results);
    }
}
