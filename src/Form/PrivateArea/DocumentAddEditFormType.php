<?php

namespace App\Form\PrivateArea;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class DocumentAddEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' => 'form-control-input'],
            ])
            ->add('document', FileType::class, [
                'mapped' => false,
                'multiple' => false,
                'label' =>false,
                'attr' => ['accept' => '.pdf'],
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => 'application/pdf'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
