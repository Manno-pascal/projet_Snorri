<?php

namespace App\Controller\Admin;

use App\Entity\Information;
use App\Enum\CategoryEnum;
use App\Enum\StatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class InformationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Information::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('title')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title',"Titre"),
            TextEditorField::new('text','Contenu'),
            DateField::new('date','Date')
        ];
    }

}
