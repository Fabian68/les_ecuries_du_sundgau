<?php

namespace App\Form;

use App\Entity\Galops;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ModifyAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('prenom')
            ->add('nom')
            ->add('dateNaissance',DateType::class,[
                'years' => range(1850,2024)
            ])
            ->add('adresse')
            ->add('telephone')
            ->add('galop', EntityType::class, [
                'class'=> Galops::class,
                'choice_label' => function ($galops) {
                    return $galops->getDisplayNiveau();
                }
            ])
            ->add('imageFile',FileType::class,[
                'required' =>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
