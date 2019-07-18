<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Galops;
use App\Entity\Images;
use App\Entity\DatesEvenements;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = \Faker\Factory::create('fr_FR');

        //Ajout des galops
        $tableauGalops=array();
        for ($i=0; $i <=7 ; $i++) { 
            $galops=new Galops();
            $galops->setNiveau($i);
            $tableauGalops[]=$galops;
            $manager->persist($galops);
        }
        $compteurJour=0;
        
        //Ajout des evenements
        for ($i=0; $i <10 ; $i++) { 
            
            $event=new Event();
            $image= new Images();
            $image->setImageName(($i+1) . ".png");
            $image->setUpdatedAt(new \DateTime());
            $manager->persist($image);
            $image->setEvenement($event);
            $event->setTitre($faker->sentence());
            $event->addImage($image);
            $event->setTexte($faker->paragraph());
            $tarif = mt_rand(10,25);
            $event->setTarifMoinsDe12($tarif/2.0);
            $event->setTarifPlusDe12($tarif);
            $event->setTarifProprietaire($tarif/4.0);
            $event->setNbMaxParticipants(mt_rand(20,150));
            //Ajout des dates et liaison
            for ($j=0; $j <=mt_rand(1,4) ; $j++) { 
                $maintenant = new \DateTime();
                $maintenant->add(new \DateInterval('P'.$compteurJour.'D'));
                $datesEvenements=new DatesEvenements();
                $datesEvenements->setDateDebut($maintenant);
                $maintenant->add(new \DateInterval('PT06H'));
                $datesEvenements->setDateFin($maintenant);
                $event->addDate($datesEvenements);
                $compteurJour++;
                $manager->persist($datesEvenements);
            }

            //Ajout des galops au evenemets
            for ($j=1; $j <=8 ; $j++) { 
                if(mt_rand(0,3)>0){
                    $galot=$tableauGalops[$j-1];
                    $event->addGalops($galot);
                    $galot->addEvenement($event);
                }
            }
            $manager->persist($event);
        }
        $manager->flush();
    }
}
