<?php

namespace App\DataFixtures;

use App\Entity\Document;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class DocumentFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) { 
            $document = new Document;
            $document->setTitle($faker->realText(45, $indexSize = 2));
            $document->setPath('uploads/documents/document-exemple.pdf');
            for ($j = 0; $j < 2; $j++) { 
                $document->addUser($this->getReference('private_owner_'. $faker->numberBetween(0, 49)));
            }
            $document->addUser($this->getReference('agent_'. $faker->numberBetween(0, 9)));
            for ($k = 0; $k < 2; $k++) { 
                $document->addUser($this->getReference('owner_'. $faker->numberBetween(0, 99)));
            }
            $manager->persist($document);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }

    public static function getGroups(): array
    {
      return ['document'];
    }
}