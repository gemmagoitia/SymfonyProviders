<?php

namespace App\Form;

use App\Entity\Provider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilder;

class ProviderForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                'label' => '>Name',
                //'attr' => ['class' => 'form-control']
            ])
            ->add('Email', EmailType::class, [
                'label' => 'Email',
                //'attr' => ['class' => 'form-control']
            ])
            ->add('Phone', TelType::class, [
                'label' => 'Phone',
                //'attr' => ['class' => 'form-control']
            ])
            ->add('Type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Hotel' => 'hotel',
                    'Pista' => 'pista',
                    'Complement'=> 'complement'
                ],
                //'attr' => ['class' => 'form-control']
            ])
            ->add('Activity', ChoiceType::class, [
                'label' => 'Activity',
                'choices' => [
                    'Actiu' => true,
                    'Inactiu' => false
                ],
                //'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Provider::class,
        ]);
    }
}