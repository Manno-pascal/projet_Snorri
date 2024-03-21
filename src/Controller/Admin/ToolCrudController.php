<?php

namespace App\Controller\Admin;

use App\Entity\Tool;
use App\Enum\CategoryEnum;
use App\Enum\StatusEnum;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;

class ToolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tool::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status')->setChoices(array_flip(StatusEnum::getTranslatedStatusList())))
            ->add(ChoiceFilter::new('category')->setChoices(array_flip(CategoryEnum::getTranslatedCategoriesList())))
            ->add('title')
            ->add('description')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre')->setColumns(5),
            TextEditorField::new('description', 'Description')->setColumns(10),
            ChoiceField::new('status')->setChoices(array_flip(StatusEnum::getTranslatedStatusList())),
            TextField::new('url', "Lien de l'outil")->setColumns(5),
            ChoiceField::new('category','Catégorie')->setColumns(3)->setChoices(array_flip(CategoryEnum::getTranslatedCategoriesList()))->hideOnIndex(),
            ImageField::new('image',"Image de l'outil")->setUploadDir('/public/uploads/image'),
            AssociationField::new("user_sender","Utilisateur")
        ];
    }

}
