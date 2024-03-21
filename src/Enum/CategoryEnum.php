<?php

namespace App\Enum;


enum CategoryEnum: string
{
    case CATEGORY_HTML = 'html';
    case CATEGORY_JAVASCRIPT = 'javascript';
    case CATEGORY_REACT = 'react';
    case CATEGORY_CCPP = 'c and cpp';
    case CATEGORY_CSHARP = 'csharp';
    case CATEGORY_PHP = 'php';
    case CATEGORY_CSS = 'css';
    case CATEGORY_SCSS = 'scss';
    case CATEGORY_BOOTSTRAP = 'bootstrap';
    case CATEGORY_NODEJS = 'nodejs';
    case CATEGORY_PYTHON = 'python';
    case CATEGORY_SYMFONY = 'symfony';
    case CATEGORY_BDD = 'database';
    case CATEGORY_OTHER = 'other';

    public static function getCategoriesList(): array
    {
        return  [
            self::CATEGORY_HTML->value,
            self::CATEGORY_JAVASCRIPT->value,
            self::CATEGORY_REACT->value,
            self::CATEGORY_CCPP->value,
            self::CATEGORY_CSHARP->value,
            self::CATEGORY_PHP->value,
            self::CATEGORY_CSS->value,
            self::CATEGORY_SCSS->value,
            self::CATEGORY_BOOTSTRAP->value,
            self::CATEGORY_NODEJS->value,
            self::CATEGORY_PYTHON->value,
            self::CATEGORY_SYMFONY->value,
            self::CATEGORY_OTHER->value
        ];
    }

    public static function getTranslatedCategoriesList(): array
    {
        return [
            self::CATEGORY_HTML->value=>"HTML",
            self::CATEGORY_JAVASCRIPT->value=>"Javascript",
            self::CATEGORY_REACT->value=>"React",
            self::CATEGORY_CCPP->value=>"C/C++",
            self::CATEGORY_CSHARP->value=>"C#",
            self::CATEGORY_PHP->value=>"PHP",
            self::CATEGORY_CSS->value=>"CSS",
            self::CATEGORY_SCSS->value=>"SCSS",
            self::CATEGORY_BOOTSTRAP->value=>"Bootstrap",
            self::CATEGORY_NODEJS->value=>"NodeJS",
            self::CATEGORY_PYTHON->value=>"Python",
            self::CATEGORY_SYMFONY->value=>"Symfony",
            self::CATEGORY_OTHER->value=>"Autre"
        ];
    }
    public static function getPrimaryCategoriesList(): array
    {
        return [
            self::CATEGORY_CSS->value,
            self::CATEGORY_HTML->value,
            self::CATEGORY_JAVASCRIPT->value,
            self::CATEGORY_PHP->value,
        ];
    }
}
