<?php

namespace App\Form;

use App\Entity\CreneauxBenevoles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class NbBenevoleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder->add('dateDebut', DateTimeType::class, [
                    'label' => 'Jour et horaire de début',
                ])
                ->add('dateFin', DateTimeType::class, [
                    'label' => 'Horaire de fin',
                ])
                ->add('nbBenevoles')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => CreneauxBenevoles::class,
            ]
        );
    }

}