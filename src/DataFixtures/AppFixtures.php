<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Image;
use App\Entity\Offer;
use App\Entity\Option;
use App\Entity\Property;
use App\Entity\PropertyType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // Fixtures property_type
        $types = ['appartement', 'maison', 'professionnel', 'terrain nu ', 'garage'];
        foreach ($types as $k => $type) {
            $propertyType = new PropertyType();
            $propertyType->setName($type);
            $manager->persist($propertyType);
            $this->addReference("type_$k", $propertyType);
        }
        
        // Fixtures option
        $optionNames = ['charges comprise', 'conventionné APL', 'meublé', 'parking', 'ascenseur', 'garage', 'jardin', 'balcon'];
        foreach ($optionNames as $k => $name) {
            $option = new Option();
            $option->setName($name);
            $manager->persist($option);
            $this->addReference("option_$k", $option);
        }

        // Fixtures city
        $dept = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '2A', '2B', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59', '60', '61', '62', '63', '64', '65', '66', '67', '68', '69', '70', '71', '72', '73', '74', '75', '76', '77', '78', '79', '80', '81', '82', '83', '84', '85', '86', '87', '88', '89', '90', '91', '92', '93', '94', '95', '971', '972', '973', '974', '976'];

        $datas = json_decode(file_get_contents("https://geo.api.gouv.fr/departements/81/communes"), true);   
        foreach ($datas as $k => $data) {
            $city = new City;
            $city->setName($data['nom']);
            $city->setZipCode($data['code']);
            $city->setDepartment($data['codeDepartement']);
            $manager->persist($city);
            $this->addReference("city_$k", $city);
        }

        // Fixtures images
        // Compte le nombre de fichiers dans le dossier image
        $pathImage = dirname(__FILE__, 3) . str_replace('/', DIRECTORY_SEPARATOR, '/public/assets/images/property/');
        $dir = opendir($pathImage);
        $fileImgCount = 0;
        while (($file = readdir($dir)) !== false) {
            if (!in_array($file, array('.', '..'))) {
                $image = new Image();
                $image->setName($file);
                $image->setPath('assets/images/property/' . $file);
                $manager->persist($image);
                $this->addReference("image_$fileImgCount", $image);
                $fileImgCount++;
            }
        }
        closedir($dir);

        // Fixtures property
        for ($i = 0; $i < 200; $i++) { 
            $property = new Property();
            $property
                ->setTransactionType(array_rand(['location' => 'location', 'vente' => 'vente']))
                ->setManager(array_rand(['propriétaire privé' => 'propriétaire privé', 'gestion agence' => 'gestion agence']))
                ->setArea(rand(20, 200))
                ->setRooms(rand(2, 10));
            $options = array_rand($optionNames, rand(2, count($optionNames)));
            foreach ($options as $index) {
                $property->addOption($this->getReference("option_$index"));
            }
            if ($property->getTransactionType() === 'location') {
                $property->setPrice(rand(30, 200) * 10);
            } else {
                $property->setPrice(rand(8, 50) * 10000);
            }
            $property
                ->setEnergy(array_rand(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E', 'F' => 'F', 'G' => 'G']))
                ->setGES(array_rand(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E', 'F' => 'F', 'G' => 'G']))
                ->setPropertyType($this->getReference("type_" . rand(0, count($types) - 1)))
                ->setCity($this->getReference("city_" . rand(0, count($datas) - 1)));
            $offer = new Offer();
            $offer
                ->setTitle($faker->realText(40, $indexSize = 2))
                ->setDescription($faker->realText(400, $indexSize = 2));
            $manager->persist($offer);
            $property->addOffer($offer);
            for ($l = 0; $l < 6; $l++) { 
                $property->addImage($this->getReference('image_' . rand(0, $fileImgCount - 1)));
            }

            $manager->persist($property);
            $this->addReference("property_$i", $property);
        }

        $manager->flush();
    }
}
