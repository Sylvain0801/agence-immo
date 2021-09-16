<?php

namespace App\DataFixtures;

use App\Entity\Message\Message;
use App\Entity\Message\UserHasMessageRead;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class MessageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) { 
            $message = new Message();
            $message->setSubject($faker->realText(50, $indexSize = 2));
            $message->setContent($faker->realText(400, $indexSize = 2));
            $message->setSender($this->getReference("agent_" . $i % 10));
            for ($j = 0; $j < 2; $j++) { 
                $msgRead = new UserHasMessageRead();
                $msgRead->setMessage($message);
                $msgRead->setRecipient($this->getReference('tenant_' . $faker->numberBetween(0, 49)));
                $manager->persist($msgRead);
            }
            for ($j = 0; $j < 2; $j++) { 
                $msgRead = new UserHasMessageRead();
                $msgRead->setMessage($message);
                $msgRead->setRecipient($this->getReference('owner_' . $faker->numberBetween(0, 49)));
                $manager->persist($msgRead);
            }
            $manager->persist($message);
        }

        for ($i = 0; $i < 20; $i++) { 
            $message = new Message();
            $message->setSubject($faker->realText(50, $indexSize = 2));
            $message->setContent($faker->realText(400, $indexSize = 2));
            $message->setSender($this->getReference("tenant_$i"));
            for ($j = 0; $j < 5; $j++) { 
                $msgRead = new UserHasMessageRead();
                $msgRead->setMessage($message);
                $msgRead->setRecipient($this->getReference('agent_' . $faker->numberBetween(0, 9)));
                $manager->persist($msgRead);
            }
            $manager->persist($message);
        }

        $manager->flush();
    }
    
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
