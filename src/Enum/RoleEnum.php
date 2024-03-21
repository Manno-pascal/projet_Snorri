<?php

namespace App\Enum;

enum RoleEnum: string
{
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_USER = 'ROLE_USER';
    case ROLE_STUDENT = 'ROLE_STUDENT';
    case ROLE_COMPANY = 'ROLE_COMPANY';

    public static function getRolesList(): ?array
    {
        return [
            self::ROLE_USER->value,
            self::ROLE_ADMIN->value,
            self::ROLE_STUDENT->value,
            self::ROLE_COMPANY->value
        ];
    }

    public static function getTranslatedRolesList(): array
    {
        return [
            self::ROLE_ADMIN->value => "Administrateur",
            self::ROLE_USER->value => "Utilisateur",
            self::ROLE_STUDENT->value => "Etudiant",
            self::ROLE_COMPANY->value => "Entreprise"
        ];
    }

}
