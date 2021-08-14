<?php

namespace App\Form\User;

use App\Entity\User\PrivateOwner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'attr' => ['class' => 'form-control-input']
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['class' => 'form-control-input']
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form-control-input',
                ],
            ])
            ->add('phone_number', TextType::class, [
                'attr' => [
                    'class' => 'form-control-input',
                    'minlength' => 5,
                    'maxlength' => 16,
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
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-control-input'],
                'error_bubbling' => true
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control-input',
                    'autocomplete' => 'new-password',
                    'minLength' => 8
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control-input',
                    'autocomplete' => 'new-password',
                    'minLength' => 8
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('public_phone', CheckboxType::class, [
                'label' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PrivateOwner::class,
        ]);
    }
}
