<?php

namespace App\DataFixtures;

use App\Entity\Galops;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurFixtures extends Fixture
{
    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        $galop = new Galops();
        $galop->setNiveau(-1);
        $manager->persist($galop);
       
       //$galop = $manager->getRepository(Galops::class)->find(1);

        $utilisateur=new Utilisateur();
        $utilisateur->setNom('Tschirhart');
        $utilisateur->setPrenom('Fabian');
        $utilisateur->setEmail('FabianTsch@gmail.com');
        $utilisateur->setDateNaissance($faker->dateTimeInInterval($startDate = '-30 years', $interval = '+ 10 days', $timezone = null));
        $utilisateur->setAdresse($faker->address);
        $utilisateur->setTelephone($faker->serviceNumber);
        $utilisateur->setMotDePasse($this->passwordEncoder->encodePassword(
                         $utilisateur,
                         '12345678'
                     ));
        $utilisateur->setRoles(array('ROLE_USER'));
        $utilisateur->setGalop($galop);
        $utilisateur->setImageName("fabian.png");
        $utilisateur->setUpdatedAt(new \DateTime());
        $manager->persist($utilisateur);

        $utilisateur=new Utilisateur();
        $utilisateur->setNom('Blackquill');
        $utilisateur->setPrenom('Simon');
        $utilisateur->setEmail('Simon.blackquill@mail.fr');
        $utilisateur->setDateNaissance($faker->dateTimeInInterval($startDate = '-30 years', $interval = '+ 10 days', $timezone = null));
        $utilisateur->setAdresse($faker->address);
        $utilisateur->setTelephone($faker->serviceNumber);
        $utilisateur->setMotDePasse($this->passwordEncoder->encodePassword(
            $utilisateur,
            'miaou123'
        )); 
        $utilisateur->setRoles(array('ROLE_ADMIN'));
        $utilisateur->setGalop( $galop);
        $utilisateur->setImageName("laurine.jpg");
        $utilisateur->setUpdatedAt(new \DateTime());
        $manager->persist($utilisateur);

        $utilisateur=new Utilisateur();
        $utilisateur->setNom('Smith');
        $utilisateur->setPrenom('John');
        $utilisateur->setEmail('John.Smith@yahoo.co.jp');
        $utilisateur->setDateNaissance($faker->dateTimeInInterval($startDate = '-30 years', $interval = '+ 10 days', $timezone = null));
        $utilisateur->setAdresse($faker->address);
        $utilisateur->setTelephone($faker->serviceNumber);
        $utilisateur->setMotDePasse($this->passwordEncoder->encodePassword(
            $utilisateur,
            'lapomme123'
        ));
        $utilisateur->setRoles(array('ROLE_ADMIN_ASSO'));
        $utilisateur->setGalop( $galop); 
        $utilisateur->setImageName("julien.jpg");
        $utilisateur->setUpdatedAt(new \DateTime());
        $manager->persist($utilisateur);
      

        $utilisateur=new Utilisateur();
        $utilisateur->setNom('LaRoche');
        $utilisateur->setPrenom('Goron');
        $utilisateur->setEmail('GLR@wanadoo.fr');
        $utilisateur->setDateNaissance($faker->dateTimeInInterval($startDate = '-30 years', $interval = '+ 10 days', $timezone = null));
        $utilisateur->setAdresse($faker->address);
        $utilisateur->setTelephone($faker->serviceNumber);
        $utilisateur->setMotDePasse($this->passwordEncoder->encodePassword(
            $utilisateur,
            'geode123'
        ));
        $utilisateur->setRoles(array('ROLE_USER'));
        $utilisateur->setGalop( $galop);
        $utilisateur->setImageName("Hugo.jpg");
        $utilisateur->setUpdatedAt(new \DateTime());
        $manager->persist($utilisateur);

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
