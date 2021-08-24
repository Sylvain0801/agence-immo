<?php

namespace App\Form\PrivateArea;

use App\Entity\Property\Property;
use App\Entity\User\Tenant;
use App\Repository\User\TenantRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyManageTenantFormType extends AbstractType
{
    private $tenantRepo;

    public function __construct(TenantRepository $tenantRepo)
    {
        $this->tenantRepo = $tenantRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('property_tenants', EntityType::class, [
                'class' => Tenant::class,
                'multiple' => true,
                'required' => false,
                'choices' => $this->tenantRepo->getListTenantsSorted()
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
