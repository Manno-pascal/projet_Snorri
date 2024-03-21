<?php

namespace App\Entity\Trait;

use App\Enum\ContractTypeEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait ContractTypesTrait
{

    #[ORM\Column(length: 255)]
    #[Groups(['offer:read'])]
    private ?string $contract_type = null;

    public function getContractType(): ?string
    {
        return $this->contract_type;
    }

    public function setContractType(string $contract_type): static
    {
        $this->contract_type = $contract_type;

        return $this;
    }
}