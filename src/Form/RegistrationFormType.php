<?php

namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company_name', TextType::class,[
                'label' => "Nom de l'entreprise",
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4',
                    'placeholder'=> 'RI7'
                ],
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4',
                    'placeholder'=> 'john-doe@domaine.fr'

                ],
            ])
            ->add('lastname', TextType::class,[
                'label' => 'Nom de famille',
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4',
                    'placeholder'=> 'Doe'
                ],
            ])
            ->add('firstname', TextType::class,[
                'label' => 'Prénom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4',
                    'placeholder'=> 'John'
                ],
            ])
            ->add('address', TextType::class,[
                'label' => 'Adresse postale',
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4',
                    'placeholder'=> '155 rue du Dirigeable, 13400 Aubagne'
                ],
            ])
            ->add('siret', NumberType::class,[
                'label' => 'Numéro siret',
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4',
                    'placeholder'=> '10000000000001'
                ],
            ])
            ->add('domain', TextType::class,[
                'label' => "Domaine d'activité",
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4',
                    'placeholder'=> 'Développement logiciels'
                ],
            ])
            ->add('phone_number', NumberType::class,[
                'label' => "Numéro de téléphone",
                'required' => true,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4',
                    'placeholder'=> '0600000000'
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'label'=>false,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control form-control-lg mt-2 mb-4',
                    'placeholder'=> 'Mot de passe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                ]
            ])

            ->add('send',SubmitType::class,[
                'label' => 'Créer un compte',
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
