<?php

namespace App\Form\PrivateArea;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $colors = [
            0 => '#9510f2', 
            1 => '#049DD9', 
            2 => '#45A165', 
            3 => '#F2BF3D', 
            4 => '#EB403D', 
            5 => '#CCCCCC'
        ];
        $repeats = [
            'weekly' => 'week',
            'monthly' => 'month',
            'annual' => 'year'
        ];

        $builder
            ->add('reminder_id', HiddenType::class)
            ->add('reminder_date', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-input',
                    'autocomplete' => 'off',
                    'pattern' => '^\d{2}\/\d{2}\/\d{2}$',
                    'placeholder' => 'jj/mm/aa'
                ],
            ])
            ->add('is_repeated', CheckboxType::class, [
                'required' => false
                ])
            ->add('frequency', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'required' => false,
                'choices' => $repeats,
                'mapped' => false,
                'placeholder' => false, 
            ])
            ->add('repeat_end', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-input',
                    'autocomplete' => 'off',
                    'pattern' => '^\d{2}\/\d{2}\/\d{2}$',
                    'placeholder' => 'jj/mm/aa',
                    'disabled' => 'disabled'
                ],
                'mapped' => false,
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control-input'],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control-input',
                    'rows' => 5
                ],
            ])
            ->add('color', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => $colors
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
