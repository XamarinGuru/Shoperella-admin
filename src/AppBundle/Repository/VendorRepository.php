<?php

namespace AppBundle\Repository;

/**
 * VendorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VendorRepository extends \Doctrine\ORM\EntityRepository
{
  // fetch Vendors within 25 miles of othe given latitude and longitude
  public function nearby($lat, $lng)
  {
      $number = 3959;
      $distance = 25;
      $sql = 'SELECT *, (' . $number . ' * acos(cos(radians(:lat)) * ' .
          'cos(radians(Latitude)) * cos(radians(Longitude) - radians(:lng)) + ' .
          'sin(radians(:lat)) * sin(radians(Latitude)))) AS distance FROM ' .
          'Vendors HAVING distance < ' . $distance . ' ORDER BY distance;';
      $params = ['lat' => $lat, 'lng' => $lng];
      return $this->getEntityManager()
          ->createQuery($sql)
          ->setParameter($sql)
          ->getResult();
  }
}