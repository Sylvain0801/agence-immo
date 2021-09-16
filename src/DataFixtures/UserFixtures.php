<?php

namespace App\DataFixtures;

use App\Entity\User\Agent;
use App\Entity\User\Owner;
use App\Entity\User\PrivateOwner;
use App\Entity\User\Tenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $passwordEncoder)
  {
      $this->passwordEncoder = $passwordEncoder;
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
          ->setRoles(['ROLE_PRIVATE_OWNER'])
          ->setIsVerified(1)
          ->setIsActive(1)
          ->setPublicPhone(rand(0, 1))
          ->setPassword($this->passwordEncoder->encodePassword($privateOwner, 'agence_immo'));

      $manager->persist($privateOwner);

      $this->addReference('private_owner_'.$i, $privateOwner);
    }
    for($i = 0; $i < 10; $i++) {

      $agent = new Agent();
  
      $agent
          ->setFirstname($faker->firstname())
          ->setLastname($faker->lastname)
          ->setEmail('agent_' . $i . '@demo.fr')
          ->setPhoneNumber($faker->phoneNumber)
          ->setAddress($faker->address)
          ->setRoles(['ROLE_AGENT'])
          ->setIsVerified(1)
          ->setIsActive(1)
          ->setPassword($this->passwordEncoder->encodePassword($agent, 'agence_immo'));

      $manager->persist($agent);

      $this->addReference('agent_'.$i, $agent);
    }

    $manager->flush();

    for($i = 0; $i < 50; $i++) {

      $owner = new Owner();
  
      $owner
          ->setFirstname($faker->firstname())
          ->setLastname($faker->lastname)
          ->setEmail('owner_' . $i . '@demo.fr')
          ->setPhoneNumber($faker->phoneNumber)
          ->setAddress($faker->address)
          ->setRoles(['ROLE_OWNER'])
          ->setAgent($this->getReference('agent_' . $i % 10))
          ->setIsVerified(1)
          ->setIsActive(1)
          ->setPassword($this->passwordEncoder->encodePassword($owner, 'agence_immo'));

      $manager->persist($owner);

      $this->addReference('owner_'.$i, $owner);
    }
    for($i = 0; $i < 200; $i++) {

      $tenant = new Tenant();
  
      $tenant
          ->setFirstname($faker->firstname())
          ->setLastname($faker->lastname)
          ->setEmail('tenant_' . $i . '@demo.fr')
          ->setPhoneNumber($faker->phoneNumber)
          ->setAddress($faker->address)
          ->setRoles(['ROLE_TENANT'])
          ->setIsVerified(1)
          ->setIsActive(1)
          ->setPassword($this->passwordEncoder->encodePassword($tenant, 'agence_immo'));

      $manager->persist($tenant);

      $this->addReference('tenant_'.$i, $tenant);
    }
    $manager->flush();
  }

  public static function getGroups(): array
  {
      return ['user'];
  }
}
