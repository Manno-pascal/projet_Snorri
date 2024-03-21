<?php

namespace App\DataFixtures;

use App\Entity\Tool;
use App\Entity\UserTool;
use App\Enum\CategoryEnum;
use App\Enum\StatusEnum;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class ToolFixtures extends Fixture implements DependentFixtureInterface
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
        for ($i = 0; $i < 200; $i++) {
            $tool = new Tool();
            $tool->setStatus($status[random_int(0, count($status) - 1)]);
            $tool->setCategory($category[random_int(0, count($category) - 1)]);
            $tool->setTitle($faker->word);
            $tool->setUrl('https://google.fr');
            $tool->setDescription($faker->paragraph(3, true));
            $tool->setUserSender($users[random_int(0, count($users) - 1)]);
            for ($j = 0; $j < 10; $j++) {
                $favorite = new UserTool();
                $favorite->setTool($tool);
                $favorite->setUser($users[random_int(0, count($users) - 1)]);
                $manager->persist($favorite);
            }
            $manager->persist($tool);
        }

        $manager->flush();
    }
}
