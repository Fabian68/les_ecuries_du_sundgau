<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Repas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BenevoleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {

        $builder->add('save', SubmitType::class, ['label' => 'Devenir benevole le matin'])
                ->add('saveAndAdd', SubmitType::class, ['label' => 'Devenir benevole l\'après-midi'])
                ->add('Save', SubmitType::class, ['label' => 'Devenir benevole pour le repas'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => SubmitType::class,
            ]
        );
    }

}