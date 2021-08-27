<?php

namespace App\Form\PrivateArea;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
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
        $builder
            ->add('start', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-input',
                    'autocomplete' => 'off'
                ],
                'mapped' => false
            ])
            ->add('end', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-input',
                    'autocomplete' => 'off'
                ],
                'mapped' => false
            ])
            ->add('all_day', CheckboxType::class, [
                'required' => false
            ])
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control-input'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control-input',
                    'rows' => 5
                ],
            ])
            ->add('background_color', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => $colors
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
