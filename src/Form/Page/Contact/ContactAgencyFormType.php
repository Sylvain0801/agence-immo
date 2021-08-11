<?php

namespace App\Form\Page\Contact;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ContactAgencyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reason' , ChoiceType::class, [
                'attr' => ['class' => 'form-control-input'],
                'expanded' => false,
                'multiple' => false,
                'choices' => $options['data']['reasons'],
                'placeholder' => $options['data']['placeholder'],
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['class' => 'form-control-input'],
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['class' => 'form-control-input'],
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'form-control-input'],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control-input',
                ],
            ])
            ->add('phone_number', TextType::class, [
                'attr' => [
                    'class' => 'form-control-input',
                    'minlength' => 5,
                    'maxlength' => 15,
                    'pattern' => "(\+|0)[1-9][0-9]{3,14}"
                ],
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Your phone number should be at least {{ limit }} characters',
                        'max' => 16,
                    ]),
                ], 
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
