<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class EventFixtures extends Fixture implements DependentFixtureInterface
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
        for ($i = 0; $i < 100; $i++) {
            $event = new Event();
            $event->setTitle($faker->word);
            $event->setDate($faker->dateTimeBetween('-10 week', '+10 week'));
            $event->setContent($faker->paragraph(3, true));
            for ($j = 0; $j < 10; $j++) {
                $event->addInscription($users[random_int(0, count($users) - 1)]);
            }
            $manager->persist($event);
        }

        $manager->flush();
    }
}
