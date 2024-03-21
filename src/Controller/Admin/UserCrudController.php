<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Enum\CategoryEnum;
use App\Enum\RoleEnum;
use App\Enum\StatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status','Status')->setChoices(array_flip(StatusEnum::getTranslatedStatusList())))
            ->add(TextFilter::new('firstname', 'Prénom'))
            ->add(TextFilter::new('lastname', 'Nom'))
            ->add(TextFilter::new('email', 'Email'))
            ->add(ChoiceFilter::new('roles', 'Role')->setChoices(array_flip(RoleEnum::getTranslatedRolesList())))
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstname', 'Prénom')->setColumns(5),
            TextField::new('lastname', 'Nom')->setColumns(5),
            EmailField::new('email', 'Email')->setColumns(5),
            ArrayField::new('roles', 'Role(s)')->setColumns(10)->hideOnDetail(),
            ChoiceField::new('roles')->setColumns(3)->setFormTypeOption('multiple', true)->setChoices(array_flip(RoleEnum::getTranslatedRolesList()))->hideOnIndex(),
            ChoiceField::new('status')->setColumns(3)->setChoices(array_flip(StatusEnum::getTranslatedStatusList())),
            TextField::new('siret', 'Numéro siret')->hideOnIndex()->setColumns(5),
            TextField::new('address', 'Adresse')->hideOnIndex()->setColumns(5),
            TextField::new('domain', "Domaine d'activité")->hideOnIndex()->setColumns(5),TextField::new('siret', 'Numéro siret')->hideOnIndex()->setColumns(5),
            TextField::new('phoneNumber', 'tél.')->hideOnIndex()->setColumns(5),
            TextField::new('companyName', "Nom de l'entreprise")->hideOnIndex()->setColumns(5),


        ];
    }

}
