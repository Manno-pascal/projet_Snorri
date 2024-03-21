<?php

namespace App\Form;

use App\Entity\Offer;

use App\Enum\ContractTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre',
                'required'=> true,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> 'Développeur Symfony H/F'
                ],
            ])
            ->add('location', ChoiceType::class,[
                'label' => 'Localisation',
                'required'=> true,
                'choices'=>['Marseille'=>'Marseille'],
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light select2-city',
                    'placeholder'=> 'Marseille - 12'
                ],
            ])
            ->add('salary', TextType::class,[
                'label' => 'Salaire brut mensuel',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> '3000'
                ],
            ])
            ->add('date', DateType::class,[
                'label' => 'Date',
                'html5' => true,
                'required'=> true,
                'widget'=>'single_text',
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                ],
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description',
                'required'=> true,
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> "GitHub  est un service web d'hébergement et de gestion de développement de logiciels"
                ],
            ])
            ->add('contract_type', ChoiceType::class,[
                'label' => 'Type de contrat',
                'required'=> true,
                'choices' => array_flip(ContractTypeEnum::getTranslatedContractsList()),
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> 'HTML'
                ],
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event,) {
                $offer = $event->getData();
                $form = $event->getForm();
                $selectedCity = $offer['location'];
                $form->add('location', ChoiceType::class, [
                    'label' => 'Localisation',
                    'choices'=>[$selectedCity],
                    'attr' => [
                        'class' => 'form-control form-control-lg mt-2 mb-4 bg-light select2-city',
                        'placeholder'=> 'Marseille - 12'
                    ],
                ]);
            });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
