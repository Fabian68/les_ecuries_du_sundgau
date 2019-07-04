<?php

namespace App\Form;

use App\Entity\DatesEvenements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class DatesEvenementsType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('dateDebut', DateTimeType::class,[
                'data' => new \DateTime("now"),
            ])
            ->add('dateFin', DateTimeType::class,[
                'data' => new \DateTime("now"),
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