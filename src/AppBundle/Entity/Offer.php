<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offer
 *
 * @ORM\Table(name="Offers")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OfferRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Offer
{
  const EXTEND_MINUTES = 60;

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
   * @ORM\ManyToOne(targetEntity="Wish")
   * @ORM\JoinColumn(name="Wish", nullable=false)
   */
  private $wish;

  /**
   * @var int
   *
   * @ORM\ManyToOne(targetEntity="Deal")
   * @ORM\JoinColumn(name="Deal", nullable=true)
   */
  private $deal;

  /**
   * @var int
   *
   * @ORM\ManyToOne(targetEntity="User")
   * @ORM\JoinColumn(name="User", nullable=false)
   */
  private $user;

  /**
   * @var int
   *
   * @ORM\ManyToOne(targetEntity="Vendor")
   * @ORM\JoinColumn(name="Vendor", nullable=false)
   */
  private $vendor;

  /**
   * @ORM\Column(name="ExpiresAt", type="datetime", nullable=true)
   */
  private $expiresAt;

  /**
   * @ORM\Column(name="ExtendedAt", type="datetime", nullable=true)
   */
  private $extendedAt;

  /**
   * @ORM\Column(name="RedeemedAt", type="datetime", nullable=true)
   */
  private $redeemedAt;

  /**
   * @ORM\Column(name="DeletedAt", type="datetime", nullable=true)
   */
  private $deletedAt;

  /**
   * @ORM\Column(name="Created", type="datetime")
   */
  private $created;

  /**
   * @ORM\Column(name="Updated", type="datetime", nullable=true)
   */
  private $updated;


  /**
   * @ORM\Column(name="Title", type="string")
   */
  private $title;

  /**
   * @ORM\Column(name="Caption", type="string")
   */
  private $caption;

  /**
   * @ORM\Column(name="Description", type="string")
   */
  private $description;

  /**
   * @ORM\ManyToMany(targetEntity="Wish", mappedBy="offer")
   */
  private $wishes;

    /**
     * @ORM\OneToOne(targetEntity="Redeemed", inversedBy="offer")
     * @ORM\JoinColumn(name="Redeemed", nullable=true)
     */
    private $redeemed;

    /**
     * @ORM\Column(name="Dismissed", type="boolean")
     */
    private $dismissed = false;

    /**
     * @ORM\Column(name="DismissedAt", type="datetime", nullable=true)
     */
    private $dismissedAt;

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

  // check if offer is still active (not expired)
  public function isActive() {
    return $this->getExpiresAt() > new \DateTime('now');
  }

  // check if the offer has been redeemed already
  public function isRedeemed() {
    return $this->getRedeemedAt() !== null;
  }

  // check if the offer is redeemable
  public function isRedeemable() {
    return $this->isActive() && !$this->isRedeemed();
  }

  // redeem an offer
  public function redeem() {
    if ($this->isRedeemable()) {
      $this->setRedeemedAt(new \DateTime('now'));
    }
  }

  // check if offer has been extended
  public function isExtended() {
    return $this->getExtendedAt() !== null;
  }

  // check if offer can be extended
  public function isExtendable() {
    return !$this->isExtended() && $this->isActive();
  }

  // extend offer
  public function extend() {
    if ($this->isExtendable()) {
        $dt = $this->getExpiresAt();
        $dt->modify('+' . self::EXTEND_MINUTES . ' minutes');
        $this->setExpiresAt($dt->format("Y-m-d H:i:s"));
        $this->setExtendedAt(new \DateTime('now'));
    }
  }

  public function isDeletable() {
    return $this->getDeletedAt() === null;
  }

  // soft-delete offer
  public function softDelete() {
    $this->setDeletedAt(new \DateTime('now'));
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
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     *
     * @return Offer
     */
    public function setExpiresAt($expiresAt)
    {
        $exp = new \DateTime();
        $this->expiresAt = $exp->setTimestamp(strtotime($expiresAt));


        return $this;
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
     * Set extendedAt
     *
     * @param \DateTime $extendedAt
     *
     * @return Offer
     */
    public function setExtendedAt($extendedAt)
    {
        $this->extendedAt = $extendedAt;

        return $this;
    }

    /**
     * Get extendedAt
     *
     * @return \DateTime
     */
    public function getExtendedAt()
    {
        return $this->extendedAt;
    }

    /**
     * Set redeemedAt
     *
     * @param \DateTime $redeemedAt
     *
     * @return Offer
     */
    public function setRedeemedAt($redeemedAt)
    {
        $this->redeemedAt = $redeemedAt;

        return $this;
    }

    /**
     * Get redeemedAt
     *
     * @return \DateTime
     */
    public function getRedeemedAt()
    {
        return $this->redeemedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Offer
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Offer
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
     * @return Offer
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
     * Set title
     *
     * @param string $title
     *
     * @return Offer
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
     * @return Offer
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
     * @return Offer
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
     * Set dismissed
     *
     * @param boolean $dismissed
     *
     * @return Offer
     */
    public function setDismissed($dismissed)
    {
        $this->dismissed = $dismissed;

        return $this;
    }

    /**
     * Get dismissed
     *
     * @return boolean
     */
    public function getDismissed()
    {
        return $this->dismissed;
    }

    /**
     * Set dismissedAt
     *
     * @param \DateTime $dismissedAt
     *
     * @return Offer
     */
    public function setDismissedAt()
    {
        $this->dismissedAt = new \DateTime("now");

        return $this;
    }

    /**
     * Get dismissedAt
     *
     * @return \DateTime
     */
    public function getDismissedAt()
    {
        return $this->dismissedAt;
    }

    /**
     * Set wish
     *
     * @param \AppBundle\Entity\Wish $wish
     *
     * @return Offer
     */
    public function setWish(\AppBundle\Entity\Wish $wish)
    {
        $this->wish = $wish;

        return $this;
    }

    /**
     * Get wish
     *
     * @return \AppBundle\Entity\Wish
     */
    public function getWish()
    {
        return $this->wish;
    }

    /**
     * Set deal
     *
     * @param \AppBundle\Entity\Deal $deal
     *
     * @return Offer
     */
    public function setDeal(\AppBundle\Entity\Deal $deal = null)
    {
        $this->deal = $deal;

        return $this;
    }

    /**
     * Get deal
     *
     * @return \AppBundle\Entity\Deal
     */
    public function getDeal()
    {
        return $this->deal;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Offer
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
     * Set vendor
     *
     * @param \AppBundle\Entity\Vendor $vendor
     *
     * @return Offer
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
     * Add wish
     *
     * @param \AppBundle\Entity\Wish $wish
     *
     * @return Offer
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
     * Set redeemed
     *
     * @param \AppBundle\Entity\Redeemed $redeemed
     *
     * @return Offer
     */
    public function setRedeemed(\AppBundle\Entity\Redeemed $redeemed = null)
    {
        $this->redeemed = $redeemed;

        return $this;
    }

    /**
     * Get redeemed
     *
     * @return \AppBundle\Entity\Redeemed
     */
    public function getRedeemed()
    {
        return $this->redeemed;
    }
}
