<?php

namespace App\DataFixtures;

use App\Entity\Thread;
use App\Entity\UserThread;
use App\Enum\CategoryEnum;
use App\Enum\StatusEnum;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class ThreadFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(readonly private UserRepository $userRepository)
    {
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager,): void
    {
        $faker = Factory::create('fr_FR');
        $status = StatusEnum::getStatusList();
        $category = CategoryEnum::getCategoriesList();
        $users = $this->userRepository->findAll();
        for ($i = 0; $i < 300; $i++) {
            $thread = new Thread();
            $thread->setStatus($status[random_int(0, count($status) - 1)]);
            $thread->setCategory($category[random_int(0, count($category) - 1)]);
            $thread->setTitle($faker->word);
            $thread->setUserCreator($users[random_int(0, count($users) - 1)]);
            for ($j = 0; $j < 10; $j++) {
                $favorite = new UserThread();
                $favorite->setThread($thread);
                $favorite->setUser($users[random_int(0, count($users) - 1)]);
                $manager->persist($favorite);
            }
            $manager->persist($thread);
        }

        $manager->flush();
    }
}
