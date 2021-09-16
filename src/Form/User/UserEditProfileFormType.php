<?php

namespace App\Form\User;

use App\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserEditProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control-input'],
            ])
            ->add('address', TextType::class, [
                'attr' => ['class' => 'form-control-input'],
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
            'data_class' => User::class,
        ]);
    }
}
