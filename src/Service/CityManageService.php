<?php

namespace App\Service;

use App\Entity\Property\City;
use App\Entity\Property\Property;
use App\Repository\Property\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CityManageService
{
  private $cityRepo;

  private $em;

  public function __construct(CityRepository $cityRepo, EntityManagerInterface $em)
  {
    $this->cityRepo = $cityRepo;
    $this->em = $em;
  }

  public function createCityIfNotExists(string $zipCode, Property $property) : void
  {
    
    $city = $this->cityRepo->findOneBy(['zip_code' => $zipCode]);
    if ($city) {
        $property->setCity($city);
    } else {
        try{
            $cityDatas = json_decode(file_get_contents("https://geo.api.gouv.fr/communes?codePostal=$zipCode&fields=centre,codeDepartement"), true);
            $city = (new City)
                ->setCoordinates($cityDatas[0]['centre']['coordinates'][1] . ',' . $cityDatas[0]['centre']['coordinates'][0])
                ->setName($cityDatas[0]['nom'])
                ->setZipCode($zipCode)
                ->setDepartment($cityDatas[0]['codeDepartement']);

            $this->em->persist($city);
            
            $property->setCity($city);
        }
        catch(\Exception $e){
            error_log($e->getMessage());
            throw new NotFoundHttpException("Une erreur s'est produite, veuillez réessayer plus tard.");
        }
    }
  }
}