<?php
namespace AppBundle\Model;

// Geocoder
//
// Geocodes the given address.
//
// Usage:
//
//    $geocoder = new Geocoder($address);
//    if ($geocoder->geocode()) {
//      $geocoder->lat();
//      $geocoder->lng();
//    } else {
//      // Geocode failed
//    }}
//
class Geocoder
{
  private $address;
  private $provider;
  private $latitude;
  private $longitude;
  private $city;
  private $state;

  public function __construct($address) {
    $curl = new \Ivory\HttpAdapter\CurlHttpAdapter();
    $key = getenv('GOOGLE_MAPS_API_KEY');
    $geocoder = new \Geocoder\Provider\GoogleMaps($curl, $locale=null,
                                                  $region=null, $ssl=true,
                                                  $key);
    $this->address = $address;
    $this->provider = $geocoder;
  }

  public function geocode() {
    try {
      $geocoded = $this->provider->geocode($this->address)->first();
      $this->latitude = $geocoded->getLatitude();
      $this->longitude = $geocoded->getLongitude();
      $this->city = $geocoded->getLocality();
      $this->state = $geocoded->getAdminLevels()->first()->getCode();
      return true;
    } catch (\Geocoder\Exception\NoResult $e) {
      return false;
    }
  }

  public function lat() {
    return $this->latitude;
  }

  public function lng() {
    return $this->longitude;
  }

  public function city() {
    return $this->city;
  }

  public function state() {
    return $this->state;
  }
}
