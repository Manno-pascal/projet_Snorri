<?php

namespace App\Form;

use App\Entity\Tool;
use App\Enum\CategoryEnum;
use App\Service\FormattedArrayHandler;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AddToolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('title', TextType::class,[
                'required'=> true,
                'label' => 'Titre',
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> 'Github'
                ],
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description',
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> "GitHub  est un service web d'hébergement et de gestion de développement de logiciels"
                ],
            ])
            ->add('category', ChoiceType::class,[
                'label' => 'Type de technologie',
                'choices' => array_flip(CategoryEnum::getTranslatedCategoriesList()),
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> 'HTML'
                ],
            ])
            ->add('url', TextType::class,[
                'label' => "Lien de l'outil",
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> 'https://github.com'
                ],
            ])

            ->add('image', FileType::class, [
                'attr' => [
                    'class' => 'form-control mt-2 form-control-lg bg-light'
                ],
                'label' => "Image de l'outil",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '15M',
                        'mimeTypes' => [
                            "image/jpg", "image/png", "image/jpeg",
                        ],
                        'mimeTypesMessage' => 'S\'il vous plait, uploadez un document valide.',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tool::class,
        ]);
    }
}
