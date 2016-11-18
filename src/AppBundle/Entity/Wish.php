<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Wishes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WishRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Wish
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @var int
   *
   * @ORM\ManyToOne(targetEntity="User")
   * @ORM\JoinColumn(name="User", nullable=false)
   */
  private $user;

  /**
   * @ORM\ManyToMany(targetEntity="Vendor", inversedBy="wishes")
   */
  private $vendor;

  /**
   * @ORM\ManyToMany(targetEntity="Offer", inversedBy="wishes")
   */
  private $offer;

  /**
   * @var string
   *
   * @ORM\Column(name="Query", type="string")
   */
  private $query;

  /**
   * @ORM\Column(name="Latitude", type="float")
   */
  private $latitude;

  /**
   * @ORM\Column(name="Longitude", type="float")
   */
  private $longitude;

  /**
   * @var datetime $created
   *
   * @ORM\Column(name="Created", type="datetime")
   */
  private $created;

  /**
   * @var datetime $updated
   * 
   * @ORM\Column(name="Updated", type="datetime", nullable = true)
   */
  private $updated;

    /**
     * @var string
     *
     * @ORM\Column(name="Hidden", type="boolean")
     */
    private $hidden = false;

    public function hide()
    {
        $this->hidden = true;

        return $this;
    }

  /**
   * @ORM\PrePersist
   */
  public function setCreatedOnPersist() {
    $this->created = new \DateTime('now');
  }

  /**
   * @ORM\PreUpdate
   */
  public function setUpdatedOnPersist() {
    $this->updated = new \DateTime('now');
  }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->vendor = new \Doctrine\Common\Collections\ArrayCollection();
        $this->offer = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set query
     *
     * @param string $query
     *
     * @return Wish
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Wish
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
     * @return Wish
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Wish
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Wish
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Wish
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add vendor
     *
     * @param \AppBundle\Entity\Vendor $vendor
     *
     * @return Wish
     */
    public function addVendor(\AppBundle\Entity\Vendor $vendor)
    {
        $this->vendor[] = $vendor;

        return $this;
    }

    /**
     * Remove vendor
     *
     * @param \AppBundle\Entity\Vendor $vendor
     */
    public function removeVendor(\AppBundle\Entity\Vendor $vendor)
    {
        $this->vendor->removeElement($vendor);
    }

    /**
     * Get vendor
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Add offer
     *
     * @param \AppBundle\Entity\Offer $offer
     *
     * @return Wish
     */
    public function addOffer(\AppBundle\Entity\Offer $offer)
    {
        $this->offer[] = $offer;

        return $this;
    }

    /**
     * Remove offer
     *
     * @param \AppBundle\Entity\Offer $offer
     */
    public function removeOffer(\AppBundle\Entity\Offer $offer)
    {
        $this->offer->removeElement($offer);
    }

    /**
     * Get offer
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * Set hidden
     *
     * @param boolean $hidden
     *
     * @return Wish
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }
}
