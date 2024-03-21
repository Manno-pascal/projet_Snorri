<?php

namespace App\Form;

use App\Entity\Thread;
use App\Enum\CategoryEnum;
use App\Service\FormattedArrayHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddThreadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('category',ChoiceType::class,[
                'label' => 'Technologie concernée',
                'choices'  => array_flip(CategoryEnum::getTranslatedCategoriesList()),
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4  bg-light',
                    'placeholder'=> 'Javascript'
                ],
            ])
            ->add('title',TextType::class,[
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> 'Comment faire une boucle if ?'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Thread::class,
        ]);
    }
}
