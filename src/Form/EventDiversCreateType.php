<?php

namespace App\Form;

use App\Entity\Event;
use App\Form\VideoType;
use App\Form\ImagesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EventDiversCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('dateDivers', DateTimeType::class, ['data' => new \DateTime("now"),'label' => 'Date'])
            ->add('texte')
            ->add('images', CollectionType::class,[
                'entry_type' => ImagesType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true
            ])
            ->add('videos', CollectionType::class,[
                'entry_type' => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
