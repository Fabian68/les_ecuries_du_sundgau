<?php

namespace App\Form;

use App\Entity\Event;
use App\Form\VideoType;
use Symfony\Component\Form\AbstractType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AddVideoType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        parent::buildForm($builder, $option);

        $builder ->add('videos', CollectionType::class,[
                'entry_type' => videoType::class,
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