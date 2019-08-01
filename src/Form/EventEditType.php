<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Galops;
use App\Entity\Images;
use App\Form\VideoType;
use App\Form\ImagesType;
use App\Entity\DatesEvenements;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EventEditType extends EventType
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
                'entry_type' => DateEvenementsEditType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('galops', EntityType::class, [
                'choice_label' => 'niveau',
                'multiple' => true,
                'expanded' => true,
                'class' => Galops::class,
            ])
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