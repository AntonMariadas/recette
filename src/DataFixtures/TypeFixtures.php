<?php

namespace App\DataFixtures;

use App\Entity\Type;
use App\Entity\Aliment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $t1 = new Type();
        $t1->setLibelle('Fruits')
            ->setImage('fruits.jpg');
        $manager->persist($t1);

        $t2 = new Type();
        $t2->setLibelle('Legumes')
            ->setImage('legumes.jpg');
        $manager->persist($t2);

        
        $repo = $manager->getRepository(Aliment::class);
        $a1 = $repo->findOneBy(['nom' => 'Patate']);
        $a1->setType($t2);
        $manager->persist($a1);

        $a2 = $repo->findOneBy(['nom' => 'Tomate']);
        $a2->setType($t2);
        $manager->persist($a2);

        $a3 = $repo->findOneBy(['nom' => 'Pomme']);
        $a3->setType($t1);
        $manager->persist($a3);

        $manager->flush();
    }
}
