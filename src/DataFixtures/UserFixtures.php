<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\RoleEnum;
use App\Enum\StatusEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(readonly private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $userAdmin = true;
        $faker = Factory::create('fr_FR');
        $status = StatusEnum::getStatusList();
        $roles = RoleEnum::getRolesList();
        $companies = ["Ecole RI7", "Thales", "Cap Gemini", "Dassault", "Le primeur du village"];
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            if ($userAdmin){
                $user->setEmail("a@a.a");
                $userAdmin = !$userAdmin;
                $user->setRoles(["ROLE_ADMIN"]);
            }else{
                $user->setEmail($faker->email());
                $user->setRoles([RoleEnum::ROLE_USER,$roles[random_int(1, count($roles)-1)]]);
            }
            $user->setLastname($faker->lastName());

            $user->setFirstname($faker->firstName($gender = null));
            $user->setAddress($faker->address());
            $user->setPassword($this->hasher->hashPassword($user, 'azerty'));
            $user->setStatus($status[random_int(0, count($status)-1)]);
            $user->setCompanyName($companies[random_int(0, count($companies)-1)]);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
