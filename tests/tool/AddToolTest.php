<?php

namespace App\Tests\tool;

use App\Entity\Tool;
use App\Entity\User;
use App\Enum\RoleEnum;
use App\Enum\StatusEnum;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddToolTest extends WebTestCase
{
    public function testAddTool(): void
    {
        $client = static::createClient();
        $user = new User();
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $repository = $entityManager->getRepository(Tool::class);
        $hasher = self::getContainer()->get('security.password_hasher');

        $user->setEmail("jane-doe@gmail.com");
        $user->setPassword($hasher->hashPassword($user, 'azerty'));
        $user->setLastname("doe");
        $user->setFirstname("jane");
        $user->setRoles([RoleEnum::ROLE_ADMIN->value]);
        $user->setAddress("23 rue de l'église");
        $user->setStatus(StatusEnum::STATUS_VALIDATED->value);

        $entityManager->persist($user);
        $entityManager->flush();

        $client->loginUser($user);
        $crawler = $client->request('GET', 'http://localhost:8000/tool/add');
        $form = $crawler->filter('#submitBtn')->form();

        $form['add_tool[title]'] = 'Docs mozilla';
        $form['add_tool[url]'] = 'https://developer.mozilla.org/fr/';
        $form['add_tool[image]']->upload('public/assets/images/placeholder_picture.jpg');
        $form['add_tool[description]']->setValue('La doc de mozilla');

        $client->submit($form);

        $this->assertSame(1, count($repository->findBy(['title'=>'Docs mozilla'])));
    }
}
