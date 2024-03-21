<?php

namespace App\DataFixtures;

use App\Entity\Offer;
use App\Enum\ContractTypeEnum;
use App\Enum\StatusEnum;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class OfferFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(readonly private UserRepository $userRepository)
    {
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $status = StatusEnum::getStatusList();
        $contract = ContractTypeEnum::getContractsList();
        $users = $this->userRepository->findAll();
        for ($i = 0; $i < 200; $i++) {
            $offer = new Offer();
            $offer->setStatus($status[random_int(0, count($status) - 1)]);
            $offer->setTitle($faker->word);
            $offer->setDescription($faker->paragraph(10, true));
            $offer->setUserCreator($users[random_int(0, count($users) - 1)]);
            $offer->setLocation($faker->city());
            $offer->setSalary(rand(1500, 3000));
            $offer->setContractType($contract[rand(0, count($contract)-1)]);
            $offer->setDate(new DateTime());
            $manager->persist($offer);
        }

        $manager->flush();
    }
}
