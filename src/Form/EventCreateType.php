<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\DatesEvenements;
use App\Entity\Galops;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EventCreateType extends EventType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        parent::buildForm($builder, $option);

        $builder
            ->add('dates', CollectionType::class,[
                'entry_type' => DatesEvenementsType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true
            ])
            ->add('galops', EntityType::class, [
                'choice_label' => 'niveau',
                'multiple' => true,
                'expanded' => true,
                'class' => Galops::class,
                'mapped' => false
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