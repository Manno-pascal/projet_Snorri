<?php

namespace App\Tests\offer;

use App\Entity\Offer;
use App\Entity\User;
use App\Enum\ContractTypeEnum;
use App\Enum\StatusEnum;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OffersByKeywordTest extends KernelTestCase
{
    public function testFindByKeyword(): void
    {
        $kernel = self::bootKernel();
        $entityManager = self::getContainer()->get('doctrine')->getManager();
        $repository = $entityManager->getRepository(Offer::class);
        $firstOffer = new Offer();
        $secondOffer = new Offer();
        $thirdOffer = new Offer();
        $user = new User();

        $user->setEmail("jean-dupont@gmail.com");
        $user->setPassword("azerty");
        $user->setLastname("dupont");
        $user->setFirstname("jean");
        $user->setAddress("23 rue de l'église");
        $user->setStatus(StatusEnum::STATUS_VALIDATED->value);

        $firstOffer->setStatus(StatusEnum::STATUS_VALIDATED->value);
        $firstOffer->setTitle('Développeur H/F');
        $firstOffer->setSalary('2596');
        $firstOffer->setDescription("Poste de développeur à RI7");
        $firstOffer->setUserCreator($user);
        $firstOffer->setLocation('Aubagne');
        $firstOffer->setContractType(ContractTypeEnum::CONTRACT_CDI->value);
        $firstOffer->setDate(new \DateTime());

        $secondOffer->setStatus(StatusEnum::STATUS_DISABLED->value);
        $secondOffer->setTitle('Développeur H/F disabled');
        $secondOffer->setSalary('2596');
        $secondOffer->setDescription("Poste de développeur à RI7");
        $secondOffer->setUserCreator($user);
        $secondOffer->setLocation('Aubagne');
        $secondOffer->setContractType(ContractTypeEnum::CONTRACT_CDI->value);
        $secondOffer->setDate(new \DateTime());

        $thirdOffer->setStatus(StatusEnum::STATUS_VALIDATED->value);
        $thirdOffer->setTitle('Développeur H/F other city');
        $thirdOffer->setSalary('2596');
        $thirdOffer->setDescription("Poste de développeur à RI7");
        $thirdOffer->setUserCreator($user);
        $thirdOffer->setLocation('Gémenos');
        $thirdOffer->setContractType(ContractTypeEnum::CONTRACT_CDI->value);
        $thirdOffer->setDate(new \DateTime());


        $entityManager->persist($user);
        $entityManager->persist($firstOffer);
        $entityManager->persist($secondOffer);
        $entityManager->persist($thirdOffer);
        $entityManager->flush();

        $results = $repository->findByFilters(1, 0, 'Aubagne', null, null);
        $response = [
            'paginatedOffers'=>[$firstOffer],
            "totalCount" => 1
        ];
        

        $this->assertSame($response, $results);
    }
}
