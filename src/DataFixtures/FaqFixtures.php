<?php

namespace App\DataFixtures;

use App\Entity\Faq\CategoryFaq;
use App\Entity\Faq\Faq;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class FaqFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      $faker = Faker\Factory::create('fr_FR');

      $categories = ['Utilisation générale du site', 'Propriétaires privés', 'Propriétaires en agence', 'Locataires', 'Autres'];

      foreach ($categories as $key => $category) {
        $categoryFaq = new CategoryFaq();
        $categoryFaq->setName($category);
        $this->addReference("categoryFaq_$key", $categoryFaq);
        $manager->persist($categoryFaq);
      }

      for ($i = 0; $i < 50; $i++) { 
        $faq = new Faq();
        $faq->setQuestion($faker->realText(65, $indexSize = 2) . ' ?')
            ->setAnswer($faker->realText(200, $indexSize = 2))
            ->setEmail($faker->email)
            ->setCategory($this->getReference("categoryFaq_" . rand(0, count($categories) - 1)));
        $manager->persist($faq);
      }

      $manager->flush();
    }
}
