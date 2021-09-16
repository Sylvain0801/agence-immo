<?php

namespace App\DataFixtures;

use App\Entity\Calendar;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CalendarFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 200 ; $i++) { 
            $tenant = $this->getReference("tenant_$i");
            $properties = $tenant->getTenantProperties();
            if (count($properties) > 0) {
                foreach ($properties as $key => $property) {
                    $rent = $property->getPrice();
                    $repeatId = null;
                    for ($j = 0; $j <= 48; $j++) { 
                        $calendar = (new Calendar())
                            ->setTenant($tenant)
                            ->setTitle('Rappel loyer')
                            ->setDescription("Echéance loyer montant : $rent €")
                            ->setStart((new DateTime())->setTimestamp(strtotime("now + $j months")))
                            ->setRepeatEnd((new DateTime)->setTimestamp(strtotime("now + 48 months")))
                            ->setFrequency('month')
                            ->setIsRepeated(true)
                            ->setColor('#049DD9'); //color blue
                        $manager->persist($calendar);
                        if ($j === 0) {
                            $manager->flush();
                            $repeatId = $calendar->getId();
                            $calendar->setRepeatId($repeatId);
                            $manager->persist($calendar);
                        } else {
                            $calendar->setRepeatId($repeatId);
                            $manager->persist($calendar);
                        }
                    }
                } 
            }
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
