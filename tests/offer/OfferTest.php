<?php

namespace App\Tests\offer;

use App\Entity\Offer;
use App\Entity\User;
use App\Enum\ContractTypeEnum;
use App\Enum\StatusEnum;
use PHPUnit\Framework\TestCase;

class OfferTest extends TestCase
{

    public function testGetDescription()
    {
        $offer = new Offer();
        $description = "Nous cherchons un développeur Kobol pour rejoindre notre équipe à l'Administration Fiscale. Vous concevrez, développerez et maintiendrez des applications critiques dans le domaine de la fiscalité. Si vous avez de solides compétences en Kobol, une passion pour la résolution de problèmes complexes et que vous souhaitez contribuer à l'efficacité de l'administration fiscale, ce poste est pour vous. Rejoignez-nous pour faire partie d'une équipe innovante et passionnée qui façonne l'avenir de la fiscalité.";
        $offer->setDescription($description);
        static::assertEquals($description, $offer->getDescription());
    }

    public function testGetTitle()
    {
        $offer = new Offer();
        $offer->setTitle('Github');
        static::assertEquals('Github', $offer->getTitle());
    }

    public function testGetAuthor()
    {
        $offer = new Offer();
        $user = new User();
        $offer->setUserCreator($user);
        static::assertEquals($user, $offer->getUserCreator());
    }

    public function testGetDate()
    {
        $offer = new Offer();
        $date = new \DateTime();
        $offer->setDate($date);
        static::assertEquals($date, $offer->getDate());
    }

    public function testGetLocation()
    {
        $offer = new Offer();
        $offer->setLocation('Marseille');
        static::assertEquals('Marseille', $offer->getLocation());
    }

    public function testGetContractType()
    {
        $offer = new Offer();
        $offer->setContractType(ContractTypeEnum::CONTRACT_CDI->value);
        static::assertEquals(ContractTypeEnum::CONTRACT_CDI->value, $offer->getContractType());
    }

    public function testGetStatus()
    {
        $offer = new Offer();
        $offer->setStatus(StatusEnum::STATUS_NEW->value);
        static::assertEquals(StatusEnum::STATUS_NEW->value, $offer->getStatus());
    }

    public function testGetSalary()
    {
        $offer = new Offer();
        $offer->setSalary('2596');
        static::assertEquals('2596', $offer->getSalary());
    }
}
