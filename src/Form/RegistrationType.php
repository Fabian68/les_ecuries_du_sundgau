<?php

namespace App\Form;

use DateTime;
use DateInterval;
use App\Entity\Galops;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('prenom')
            ->add('nom')
            ->add('galop', EntityType::class, [
                'class'=> Galops::class,
                'choice_label' => function ($galops) {
                    return $galops->getDisplayNiveau();
                }
            ])
            ->add('imageFile',FileType::class,[
                'required' =>false
            ])
            ->add('motDePasse',PasswordType::class)
            ->add('confirm_motDePasse',PasswordType::class)
            ->add('dateNaissance',DateType::class,[
                'data' => (new \DateTime('1995-05-14')),
                'years' => range(1850,2024)
            ])
            ->add('adresse',TextareaType::class)
            ->add('telephone')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
