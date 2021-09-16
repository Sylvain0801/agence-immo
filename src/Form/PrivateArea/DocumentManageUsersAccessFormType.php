<?php

namespace App\Form\PrivateArea;

use App\Entity\Document\DocHasSeen;
use App\Entity\Document\Document;
use App\Repository\User\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentManageUsersAccessFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('users', EntityType::class, [
                'class' => DocHasSeen::class,
                'choice_label' => 'user',
                'multiple' => true,
                'required' => false,
                'by_reference' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class
        ]);
    }
}
