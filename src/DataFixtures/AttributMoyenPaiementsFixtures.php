<?php

namespace App\DataFixtures;

use App\Entity\AttributMoyenPaiements;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AttributMoyenPaiementsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $moyenPaiement = new AttributMoyenPaiements();
        $moyenPaiement->setLibelle('En ligne');
        $manager->persist($moyenPaiement);

        $moyenPaiement = new AttributMoyenPaiements();
        $moyenPaiement->setLibelle('Sur place');
        $manager->persist($moyenPaiement);
        $manager->flush();
    }
}
