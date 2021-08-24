<?php

namespace App\Form\PrivateArea;

use App\Entity\Property\Option;
use App\Entity\Property\Property;
use App\Entity\Property\PropertyType;
use App\Entity\User\Owner;
use App\Repository\User\OwnerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Contracts\Translation\TranslatorInterface;

class PropertyAddEditFormType extends AbstractType
{
    private $translator;

    private $ownerRepo;

    public function __construct(TranslatorInterface $translator, OwnerRepository $ownerRepo)
    {
        $this->translator = $translator;
        $this->ownerRepo = $ownerRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transaction_type', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    $this->translator->trans('sale') => 'sale',
                    $this->translator->trans('rental') => 'rental',
                ],
            ])
            ->add('property_type', EntityType::class, [
                'expanded' => true,
                'multiple' => false,
                'class' => PropertyType::class,
                'choice_label' => 'name',
            ])
            ->add('area', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control-input',
                    'min' => 10,
                    'max' => 2000
                ],
                'constraints' => [
                    new GreaterThan([
                        'value' => 10,
                        'message' => 'Area should be at least {{ limit }} m²',
                    ]),
                    new LessThan([
                        'value' => 2000,
                    ]),
                ], 
            ])
            ->add('rooms', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control-input',
                    'min' => 1,
                    'max' => 99
                ],
                'constraints' => [
                    new GreaterThan([
                        'value' => 1,
                        'message' => 'There must be at least {{ limit }} room',
                    ]),
                    new LessThan([
                        'value' => 99,
                    ]),

                ], 
            ])
            ->add('price', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control-input',
                    'min' => 10,
                    'max' => 999999999
                ],
                'constraints' => [
                    new GreaterThan([
                        'value' => 10,
                        'message' => 'The price must be at least {{ value }} €'
                    ]),
                    new LessThan([
                        'value' => 999999999,
                    ]),
                ], 
            ])
            ->add('energy', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E',
                    'F' => 'F',
                    'G' => 'G'
                ],
            ])
            ->add('ges', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E',
                    'F' => 'F',
                    'G' => 'G'
                ],
            ])
            ->add('options', EntityType::class, [
                'expanded' => true,
                'multiple' => true,
                'class' => Option::class,
                'choice_label' => 'name',
                'mapped' => false,
                'required'=> false,
            ])
            ->add('city', TextareaType::class, [
                'mapped' => false
            ])
            ->add('owner_property', EntityType::class, [
                'class' => Owner::class,
                'choices' => $this->ownerRepo->getListOwnersSorted(),
                'attr' => [
                    'class' => 'form-control-input',
                ],
                'placeholder' => $this->translator->trans('-- Choose an owner from the list --')
            ])
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
