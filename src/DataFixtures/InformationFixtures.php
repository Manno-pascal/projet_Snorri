<?php

namespace App\DataFixtures;

use App\Entity\Information;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class InformationFixtures extends Fixture implements DependentFixtureInterface
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
        $users = $this->userRepository->findAll();
        for ($i = 0; $i < 10; $i++) {
            $news = new Information();
            $news->setTitle($faker->word);
            $news->setUserCreator($users[random_int(0, count($users) - 1)]);
            $news->setText($faker->paragraph);
            $news->setImage("/assets/images/placeholder_picture.jpg");
            $manager->persist($news);
        }

        $manager->flush();
    }
}
