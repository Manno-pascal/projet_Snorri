<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreadMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',TextareaType::class,[
                'label' => 'Contenu',
                'attr' => [
                    'class' => 'form-control form-control-lg mt-2 mb-4 bg-light',
                    'placeholder'=> "Bonjour, on m'a parlé une fois d'une \"boucle if\" mais je comprends pas ..."
                ],
            ])
            ->add('thread',AddThreadType::class,[
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
