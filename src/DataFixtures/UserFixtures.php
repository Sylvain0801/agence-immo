<?php

namespace App\DataFixtures;

use App\Entity\User\Agent;
use App\Entity\User\Owner;
use App\Entity\User\PrivateOwner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserFixtures extends Fixture
{
  private $userPasswordHasher;

  public function __construct(UserPasswordHasherInterface $userPasswordHasher)
  {
      $this->userPasswordHasher = $userPasswordHasher;
  }
  
  public function load(ObjectManager $manager)
  {

    $faker = Faker\Factory::create('fr_FR');

    for($i = 0; $i < 50; $i++) {

      $privateOwner = new PrivateOwner();
  
      $privateOwner
          ->setFirstname($faker->firstname())
          ->setLastname($faker->lastname)
          ->setEmail('private_owner_' . $i . '@demo.fr')
          ->setPhoneNumber($faker->phoneNumber)
          ->setAddress($faker->address)
          ->setRoles(['PRIVATE_OWNER'])
          ->setIsVerified(1)
          ->setIsActive(1)
          ->setPublicPhone(rand(0, 1))
          ->setPassword($this->userPasswordHasher->hashPassword($privateOwner, 'agence_immo'));

      $manager->persist($privateOwner);

      $this->addReference('private_owner_'.$i, $privateOwner);
    }
    for($i = 0; $i < 50; $i++) {

      $agent = new Agent();
  
      $agent
          ->setFirstname($faker->firstname())
          ->setLastname($faker->lastname)
          ->setEmail('agent_' . $i . '@demo.fr')
          ->setPhoneNumber($faker->phoneNumber)
          ->setAddress($faker->address)
          ->setRoles(['AGENT'])
          ->setIsVerified(1)
          ->setIsActive(1)
          ->setPassword($this->userPasswordHasher->hashPassword($agent, 'agence_immo'));

      $manager->persist($agent);

      $this->addReference('agent_'.$i, $agent);
    }
    for($i = 0; $i < 100; $i++) {

      $owner = new Owner();
  
      $owner
          ->setFirstname($faker->firstname())
          ->setLastname($faker->lastname)
          ->setEmail('owner_' . $i . '@demo.fr')
          ->setPhoneNumber($faker->phoneNumber)
          ->setAddress($faker->address)
          ->setRoles(['OWNER'])
          ->setIsVerified(1)
          ->setIsActive(1)
          ->setPassword($this->userPasswordHasher->hashPassword($owner, 'agence_immo'));

      $manager->persist($owner);

      $this->addReference('owner_'.$i, $owner);
    }
    $manager->flush();
  }
}
