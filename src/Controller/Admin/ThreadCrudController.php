<?php

namespace App\Controller\Admin;

use App\Entity\Thread;
use App\Enum\CategoryEnum;
use App\Enum\StatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class ThreadCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Thread::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status')->setChoices(array_flip(StatusEnum::getTranslatedStatusList())))
            ->add(ChoiceFilter::new('category')->setChoices(array_flip(CategoryEnum::getTranslatedCategoriesList())))
            ->add('title')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre')->setColumns(5),
            AssociationField::new("user_creator","Nom du créateur")
        ];
    }

}
