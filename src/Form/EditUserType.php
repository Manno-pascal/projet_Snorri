<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class,[
                'label' => 'Adresse Email',
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4  bg-light',
                    'placeholder'=> 'john-doe@exemple.fr'
                ],
            ])
            ->add('password', PasswordType::class,[
                'label' => 'Mot de passe',
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> 'Mot de passe'
                ],
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Nom de famille',
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> 'Doe'
                ],
            ])
            ->add('firstname', TextType::class,[
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> 'John'
                ],
            ])
            ->add('send',SubmitType::class,[
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-lg btn-primary mt-2 mb-4'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
