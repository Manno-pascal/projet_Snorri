<?php

namespace App\Enum;

enum ContractTypeEnum: string
{
    case CONTRACT_CDI = 'CDI';
    case CONTRACT_CDD = 'CDD';
    case CONTRACT_INTERIM = 'Interim';
    case CONTRACT_STAGE = 'Stage';
    case CONTRACT_ALTERNANCE = 'Alternance';

    public static function getContractsList(): ?array
    {
        return [
            self::CONTRACT_ALTERNANCE->value,
            self::CONTRACT_CDD->value,
            self::CONTRACT_CDI->value,
            self::CONTRACT_INTERIM->value,
            self::CONTRACT_STAGE->value,
        ];
    }

    public static function getTranslatedContractsList(): array
    {
        return [
            self::CONTRACT_ALTERNANCE->value=>'Alternance',
            self::CONTRACT_CDD->value=>'CDD',
            self::CONTRACT_CDI->value=>'CDI',
            self::CONTRACT_INTERIM->value=>'Interim',
            self::CONTRACT_STAGE->value=>'Stage',
        ];
    }

    public static function getColorContractType(): array
    {
        return [
            self::CONTRACT_ALTERNANCE->value=>"#CE6A6B",
            self::CONTRACT_CDD->value=>"#EBACA2",
            self::CONTRACT_CDI->value=>"#BED3C3",
            self::CONTRACT_INTERIM->value=>"#4A919E",
            self::CONTRACT_STAGE->value=>"#212E53",
        ];
    }
    
}
