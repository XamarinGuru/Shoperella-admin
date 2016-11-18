<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AutoResponseOffer
 *
 * @ORM\Table(name="auto_response_offer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AutoResponseOfferRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class AutoResponseOffer
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
     * @var \DateTime
     *
     * @ORM\Column(name="expiresAt", type="datetime")
     */
    private $expiresAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="Wish")
     */
    private $wishes;

    /**
     * @ORM\ManyToOne(targetEntity="Vendor")
     */
    private $vendor;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=255, nullable=true)
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=1000)
     */
    private $description;

    /**
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;

    /**
     * @ORM\PrePersist
     */
    public function setCreatedOnPersist() {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedOnUpdate() {
        $this->updatedAt = new \DateTime('now');
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
     * @return AutoResponseOffer
     */
    public function setExpiresAt($expiresAt)
    {
        $dt = new \Datetime;
        $this->expiresAt = $dt->setTimestamp(strtotime($expiresAt));

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
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return AutoResponseOffer
     */
    public function setDeletedAt()
    {
        $this->deletedAt = new \DateTime("now");

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
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return AutoResponseOffer
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return AutoResponseOffer
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
     * @return AutoResponseOffer
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
     * @return AutoResponseOffer
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
     * Add wish
     *
     * @param \AppBundle\Entity\Wish $wish
     *
     * @return AutoResponseOffer
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
     * Set vendor
     *
     * @param \AppBundle\Entity\Vendor $vendor
     *
     * @return AutoResponseOffer
     */
    public function setVendor(\AppBundle\Entity\Vendor $vendor = null)
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return AutoResponseOffer
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set deleted
     *
     * @param boolean $deleted
     *
     * @return AutoResponseOffer
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return boolean
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}
