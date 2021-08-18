<?php

namespace App\Service;

use App\Entity\Property\Image;
use App\Entity\Property\Offer;
use Doctrine\ORM\EntityManagerInterface;

class FileManageService
{
  private $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function add($images, Offer $offer)
  {
    $filePath = (dirname(__FILE__, 3).str_replace('/', DIRECTORY_SEPARATOR, '/public/uploads/images/'));

    foreach ($images as $key => $image) {

        $newFileName = md5(uniqid()).'.'.$image->guessExtension();
        $image->move($filePath, $newFileName);

        $img = new Image();
        $img->setName($image->getClientOriginalName());
        $img->setPath('uploads/images/'.$newFileName);

        $this->em->persist($img);

        $offer->addImage($img);
    }
  }
}