<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('titre')
            /**->add('dates', CollectionType::class, [
             'entry_type' => DateTimeType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => true
            ])*/
            ->add('tarifMoinsDe12')
            ->add('plusDe12')
            ->add('proprietaire')
            ->add('image')
            ->add('texte')
            ->add('nbMaxParticipants');
        // ->add('galops')
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