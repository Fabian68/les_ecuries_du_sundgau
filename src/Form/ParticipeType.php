<?php

namespace App\Form;

use App\Entity\Utilisateur;
use App\Entity\Repas;
use App\Entity\AttributMoyenPaiements;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ParticipeType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $option
     */
    public function buildForm(FormBuilderInterface $builder, array $option)
    {
        $builder
            ->add('Paiement', EntityType::class, [
                'choice_label' => 'Libelle',
                'multiple' => false,
                'mapped' => false,
                'class' => AttributMoyenPaiements::class,
            ])
            ->add('save', SubmitType::class, ['label' => 'S\'inscrire'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Utilisateur::class,
            ]
        );
    }

}