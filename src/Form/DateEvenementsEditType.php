<?php

namespace App\Form;

use App\Entity\DatesEvenements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DateEvenementsEditType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('dateDebut', DateTimeType::class, [
                'label' => 'Jour et horaire de début',
            ])
            ->add('dateFin', DateTimeType::class, [
                'label' => 'Horaire de fin',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => DatesEvenements::class,
            ]
        );
    }

}