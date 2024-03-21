<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Enum\CategoryEnum;
use App\Enum\ContractTypeEnum;
use App\Enum\StatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status')->setChoices(array_flip(StatusEnum::getTranslatedStatusList())))
            ->add(ChoiceFilter::new('category')->setChoices(array_flip(CategoryEnum::getTranslatedCategoriesList())))
            ->add(EntityFilter::new('user_creator'))
            ->add('title')
            ->add('description')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre')->setColumns(5),
            TextField::new('location', 'Localisation')->setColumns(5),
            NumberField::new('salary', 'Salaire brut/mois')->setColumns(5),
            DateField::new('date','Date de début'),
            TextEditorField::new('description'),
            ChoiceField::new('status')->setChoices(array_flip(StatusEnum::getTranslatedStatusList())),
            ChoiceField::new('contractType')->setChoices(array_flip(ContractTypeEnum::getTranslatedContractsList())),
            AssociationField::new("user_creator","Nom de l'entreprise")
        ];
    }

}
