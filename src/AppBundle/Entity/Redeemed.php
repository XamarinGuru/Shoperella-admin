<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Redeemed
 *
 * @ORM\Table(name="redeemed")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RedeemedRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Redeemed
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
     * @ORM\ManyToOne(targetEntity="Deal", inversedBy="redemptions")
     * @ORM\JoinColumn(name="deal", nullable=true)
     */
    private $deal;

    /**
     * @ORM\OneToOne(targetEntity="Offer", mappedBy="redeemed")
     * @ORM\JoinColumn(name="offer", nullable=true)
     */
    private $offer;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="redemptions")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="redeemedAt", type="datetime")
     */
    private $redeemedAt;

    /**
     * @ORM\PrePersist
     */
    public function lifecycleRedeemedAt()
    {
        $this->redeemedAt = new \DateTime("now");

        return $this;
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
     * Set user
     *
     * @param string $user
     *
     * @return Redeemed
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set redeemedAt
     *
     * @param \DateTime $redeemedAt
     *
     * @return Redeemed
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
     * Set deal
     *
     * @param \AppBundle\Entity\Deal $deal
     *
     * @return Redeemed
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
     * Set offer
     *
     * @param \AppBundle\Entity\Offer $offer
     *
     * @return Redeemed
     */
    public function setOffer(\AppBundle\Entity\Offer $offer = null)
    {
        $this->offer = $offer;

        return $this;
    }

    /**
     * Get offer
     *
     * @return \AppBundle\Entity\Offer
     */
    public function getOffer()
    {
        return $this->offer;
    }
}
