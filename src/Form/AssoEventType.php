<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\CreneauxBenevoles;
use App\Form\NbBenevoleType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AssoEventType extends NbBenevoleType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder->add('creneauxBenevoles', CollectionType::class,[
            'entry_type' => NbBenevoleType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'by_reference' => false,
            
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Event::class,
            ]
        );
    }

}