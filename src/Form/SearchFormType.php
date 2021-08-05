<?php

namespace App\Form;

use App\Entity\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class SearchFormType extends AbstractType
{
    
    private $propertyRepository;

    private $translator;

    public function __construct(PropertyRepository $propertyRepository, TranslatorInterface $translator)
    {
        $this->propertyRepository = $propertyRepository;
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sell_price_min', IntegerType::class, [
                'label' => 'Prix min',
                'attr' => [
                    'min' => 0,
                    'step' => 1000
                ]
            ])
            ->add('sell_price_max', IntegerType::class, [
                'label' => 'Prix max',
                'attr' => [
                    'min' => 0,
                    'step' => 1000
                ]
            ])
            ->add('rent_price_min', IntegerType::class, [
                'label' => 'Alquiler min',
                'attr' => [
                    'min' => 0,
                    'step' => 100
                ]
            ])
            ->add('rent_price_max', IntegerType::class, [
                'label' => 'Alquiler max',
                'attr' => [
                    'min' => 0,
                    'step' => 100
                ]
            ])
            ->add('rooms', IntegerType::class, [
                'label' => 'Habitaciones',
                'attr' => [
                    'min' => 1,
                    'max' => 99,
                ]
            ])
            ->add('type', EntityType::class, [
                'label' => 'Type',
                'class' => PropertyType::class,
                'choice_label' => 'name'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('radius', IntegerType::class, [
                'label' => 'Rayon',
                'attr' => [
                    'value' => 5,
                    'min' => 5,
                    'max' => 150,
                    'step' => 10
                ]
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
