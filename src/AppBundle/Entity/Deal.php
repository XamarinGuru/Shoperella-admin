<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Deal
 *
 * @ORM\Table(name="Deals")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DealRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Deal
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
   * @ORM\ManyToOne(targetEntity="Vendor")
   * @ORM\JoinColumn(name="Vendor", nullable=false)
   */
  private $vendor;

  /**
   * @var string
   *
   * @ORM\Column(name="Title", type="string", length=255)
   */
  private $title;

  /**
   * @var string
   *
   * @ORM\Column(name="Caption", type="string", length=255)
   */
  private $caption;

  /**
   * @var string
   *
   * @ORM\Column(name="Description", type="text")
   */
  private $description;

  /**
   * @ORM\Column(name="HoursAvailable", type="integer", nullable=true)
   */
  private $hoursAvailable;

  /**
   * @ORM\Column(name="ExpiresAt", type="datetime", nullable=true)
   */
  private $expiresAt;

  /**
   * @ORM\Column(name="DailyDeal", type="boolean", nullable=true)
   */
  private $dailyDeal = 0;

  /**
   * @ORM\Column(name="Created", type="datetime")
   */
  private $created;

  /**
   * @ORM\Column(name="Updated", type="datetime", nullable=true)
   */
  private $updated;

    /**
     * @ORM\Column(name="deleted", type="integer")
     */
    private $deleted = 0;


    /**
     * @ORM\OneToMany(targetEntity="Redeemed", mappedBy="deal")
     * @ORM\JoinColumn(name="redemptions", nullable=true)
     */
  private $redemptions;

  /**
   * @ORM\PrePersist
   */
  public function setCreatedOnPersist() {
    $this->created = new \DateTime('now');
  }

  /**
   * @ORM\PreUpdate
   */
  public function setUpdatedOnUpdate() {
    $this->updated = new \DateTime('now');
  }


    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     *
     * @return Deal
     */
    public function setExpiresAt($expiresAt)
    {
        $dt = new \DateTime;
        $this->expiresAt = $dt->setTimestamp(strtotime($expiresAt));

        return $this;
    }

    /**
     * Set deleted
     *
     * @param integer $deleted
     *
     * @return Deal
     */
    public function setDeleted($deleted)
    {
        if ($deleted === true)
        {
            $this->deleted = true;
        } else {
            $this->deleted = false;
        }

        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->redemptions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Deal
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set caption
     *
     * @param string $caption
     *
     * @return Deal
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Deal
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set hoursAvailable
     *
     * @param integer $hoursAvailable
     *
     * @return Deal
     */
    public function setHoursAvailable($hoursAvailable)
    {
        $this->hoursAvailable = $hoursAvailable;

        return $this;
    }

    /**
     * Get hoursAvailable
     *
     * @return integer
     */
    public function getHoursAvailable()
    {
        return $this->hoursAvailable;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set dailyDeal
     *
     * @param boolean $dailyDeal
     *
     * @return Deal
     */
    public function setDailyDeal($dailyDeal)
    {
        $this->dailyDeal = $dailyDeal;

        return $this;
    }

    /**
     * Get dailyDeal
     *
     * @return boolean
     */
    public function getDailyDeal()
    {
        return $this->dailyDeal;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Deal
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
     * @return Deal
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
     * Get deleted
     *
     * @return integer
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set vendor
     *
     * @param \AppBundle\Entity\Vendor $vendor
     *
     * @return Deal
     */
    public function setVendor(\AppBundle\Entity\Vendor $vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Get vendor
     *
     * @return \AppBundle\Entity\Vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Add redemption
     *
     * @param \AppBundle\Entity\Redeemed $redemption
     *
     * @return Deal
     */
    public function addRedemption(\AppBundle\Entity\Redeemed $redemption)
    {
        $this->redemptions[] = $redemption;

        return $this;
    }

    /**
     * Remove redemption
     *
     * @param \AppBundle\Entity\Redeemed $redemption
     */
    public function removeRedemption(\AppBundle\Entity\Redeemed $redemption)
    {
        $this->redemptions->removeElement($redemption);
    }

    /**
     * Get redemptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRedemptions()
    {
        return $this->redemptions;
    }
}
