<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\Geocoder;

/**
 * Vendor
 *
 * @ORM\Table(name="Vendors")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VendorRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Vendor
{
  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var int
   *
   * @ORM\ManyToOne(targetEntity="User")
   * @ORM\JoinColumn(name="Owner", nullable=false)
   */
  private $owner;

  /**
   * @var string
   *
   * @ORM\Column(name="Name", type="string", length=255, nullable=false)
   */
  private $name;

  /**
   * @var string
   *
   * @ORM\Column(name="ImagePath", type="string", length=255, nullable=true)
   */
  private $imagePath;

  /**
   * @ORM\Column(name="Street1", type="string", length=255, nullable=false)
   */
  private $street1;

  /**
   * @ORM\Column(name="Street2", type="string", length=255, nullable=true)
   */
  private $street2;

  /**
   * @ORM\Column(name="City", type="string", length=75, nullable=false)
   */
  private $city;

  /**
   * @ORM\Column(name="State", type="string", length=2, nullable=false)
   */
  private $state;

  /**
   * @ORM\Column(name="Zip", type="string", length=5, nullable=false)
   */
  private $zip;

  /**
   * @ORM\Column(name="Latitude", type="float", nullable=true)
   */
  private $latitude;

  /**
   * @ORM\Column(name="Longitude", type="float", nullable=true)
   */
  private $longitude;

  /**
   * @ORM\Column(name="autoOfferStatus", type="integer", length=1, nullable=false)
   */
  private $autoOfferStatus = false;

  /**
   * @ORM\ManyToMany(targetEntity="Wish", mappedBy="vendor")
   */
  private $wishes;

    /**
     * @ORM\OneToOne(targetEntity="AutoResponseOffer")
     * @ORM\JoinColumn(name="ActiveAutoResponseOffer", nullable=true)
     */
  private $activeAutoResponseOffer;

  /**
   * @ORM\PrePersist
   */
  public function onPersist() {
    $this->geocode();
  }

  // Geocode this address and set the latitude and longitude.
  public function geocode() {
    $parts = [$this->street1, $this->zip];
    $address = join(', ', $parts);
    $geocoder = new Geocoder($address);
    if ($geocoder->geocode()) {
      $this->setLatitude($geocoder->lat());
      $this->setLongitude($geocoder->lng());
      $this->setCity($geocoder->city());
      $this->setState($geocoder->state());
    } else {
      // Geocoding failed
      // TODO Log Failure
    }
  }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->wishes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Vendor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set imagePath
     *
     * @param string $imagePath
     *
     * @return Vendor
     */
    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    /**
     * Get imagePath
     *
     * @return string
     */
    public function getImagePath()
    {
        return $this->imagePath;
    }

    /**
     * Set street1
     *
     * @param string $street1
     *
     * @return Vendor
     */
    public function setStreet1($street1)
    {
        $this->street1 = $street1;

        return $this;
    }

    /**
     * Get street1
     *
     * @return string
     */
    public function getStreet1()
    {
        return $this->street1;
    }

    /**
     * Set street2
     *
     * @param string $street2
     *
     * @return Vendor
     */
    public function setStreet2($street2)
    {
        $this->street2 = $street2;

        return $this;
    }

    /**
     * Get street2
     *
     * @return string
     */
    public function getStreet2()
    {
        return $this->street2;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Vendor
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Vendor
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return Vendor
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Vendor
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Vendor
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set autoOfferStatus
     *
     * @param integer $autoOfferStatus
     *
     * @return Vendor
     */
    public function setAutoOfferStatus($autoOfferStatus)
    {
        $this->autoOfferStatus = $autoOfferStatus;

        return $this;
    }

    /**
     * Get autoOfferStatus
     *
     * @return integer
     */
    public function getAutoOfferStatus()
    {
        return $this->autoOfferStatus;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\User $owner
     *
     * @return Vendor
     */
    public function setOwner(\AppBundle\Entity\User $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Add wish
     *
     * @param \AppBundle\Entity\Wish $wish
     *
     * @return Vendor
     */
    public function addWish(\AppBundle\Entity\Wish $wish)
    {
        $this->wishes[] = $wish;

        return $this;
    }

    /**
     * Remove wish
     *
     * @param \AppBundle\Entity\Wish $wish
     */
    public function removeWish(\AppBundle\Entity\Wish $wish)
    {
        $this->wishes->removeElement($wish);
    }

    /**
     * Get wishes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWishes()
    {
        return $this->wishes;
    }

    /**
     * Set activeAutoResponseOffer
     *
     * @param \AppBundle\Entity\AutoResponseOffer $activeAutoResponseOffer
     *
     * @return Vendor
     */
    public function setActiveAutoResponseOffer(\AppBundle\Entity\AutoResponseOffer $activeAutoResponseOffer = null)
    {
        $this->activeAutoResponseOffer = $activeAutoResponseOffer;

        return $this;
    }

    /**
     * Get activeAutoResponseOffer
     *
     * @return \AppBundle\Entity\AutoResponseOffer
     */
    public function getActiveAutoResponseOffer()
    {
        return $this->activeAutoResponseOffer;
    }
}
