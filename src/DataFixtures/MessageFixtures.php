<?php

namespace App\DataFixtures;

use App\Entity\Message;
use App\Enum\StatusEnum;
use App\Repository\ThreadRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(readonly private UserRepository $userRepository,readonly private ThreadRepository $threadRepository)
    {
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class, ThreadFixtures::class];
    }

    public function load(ObjectManager $manager,): void
    {
        $faker = Factory::create('fr_FR');
        $users = $this->userRepository->findAll();
        $threads = $this->threadRepository->findAll();
        $status = StatusEnum::getStatusList();
        foreach ($threads as $thread){
            for ($i = 0; $i<random_int(0, 50);$i++){
                $message = new Message();
                $message->setUserCreator($users[random_int(0, count($users) - 1)]);
                $message->setStatus($status[random_int(0, count($status) - 1)]);
                $message->setContent($faker->paragraph(2,true));
                $message->setThread($thread);
                $manager->persist($message);
            }
            $manager->flush();
        }
    }
}
