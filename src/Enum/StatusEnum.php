<?php

namespace App\Enum;

enum StatusEnum: string
{

    case STATUS_NEW = 'New';
    case STATUS_VALIDATED = 'Validated';
    case STATUS_DISABLED = 'Disabled';



    public static function getStatusList(): ?array
    {
        return [
            self::STATUS_VALIDATED->value,
            self::STATUS_NEW->value,
            self::STATUS_DISABLED->value
        ];
    }


    public static function getTranslatedStatusList(): array
    {
        return [
            self::STATUS_VALIDATED->value => "Validé",
            self::STATUS_NEW->value => "Nouveau",
            self::STATUS_DISABLED->value => "Désactivé"
        ];
    }


    public static function getTextColorStatusList(): array
    {
        return [
            self::STATUS_VALIDATED->value => "text-success",
            self::STATUS_NEW->value => "text-primary",
            self::STATUS_DISABLED->value => "text-danger"
        ];
    }
}
