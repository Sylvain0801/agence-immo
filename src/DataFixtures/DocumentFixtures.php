<?php

namespace App\DataFixtures;

use App\Entity\Document\DocHasSeen;
use App\Entity\Document\Document;
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

        for ($i = 0; $i < 200; $i++) { 
            $document = (new Document)
                ->setTitle($faker->realText(45, $indexSize = 2))
                ->setPath('uploads/documents/document-exemple.pdf');

            $docHasSeen = (new DocHasSeen)
                ->setDocument($document)
                ->setUser($this->getReference('private_owner_'. $faker->numberBetween(0, 49)));
            $manager->persist($docHasSeen);

            $docHasSeen = (new DocHasSeen)
                ->setDocument($document)
                ->setUser($this->getReference('agent_'. $faker->numberBetween(0, 9)));
            $manager->persist($docHasSeen);

            $docHasSeen = (new DocHasSeen)
                ->setDocument($document)
                ->setUser($this->getReference('owner_'. $faker->numberBetween(0, 49)));
            $manager->persist($docHasSeen);

            $docHasSeen = (new DocHasSeen)
                ->setDocument($document)
                ->setUser($this->getReference('tenant_'. $faker->numberBetween(0, 199)));
            $manager->persist($docHasSeen);

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