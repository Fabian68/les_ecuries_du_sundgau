<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\CreneauxBenevoles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\CreneauxBenevolesRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BenevoleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('creneaux', EntityType::class, [
            'query_builder' => function(CreneauxBenevolesRepository $repo) use ($options) {
                return $repo->getCreneauxForQueryBuilder($options['id']);
            },
            'choice_label' => 'creneauxFormatted',
            'multiple' => true,
            'expanded' => true,
            'class' => CreneauxBenevoles::class,
        ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => null,
                'id' => 2,
            ]
        );
    }

}