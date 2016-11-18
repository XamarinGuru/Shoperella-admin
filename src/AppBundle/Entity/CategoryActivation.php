<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * CategoryActivation
 *
 * @ORM\Table(name="CategoryActivations")
 * @UniqueEntity(fields={"category", "vendor"})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryActivationRepository")
 */
class CategoryActivation
{
  /**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity="Category")
   * @ORM\JoinColumn(name="Category")
   */
  private $category;

  /**
   * @ORM\ManyToOne(targetEntity="Vendor")
   * @ORM\JoinColumn(name="Vendor")
   */
  private $vendor;

  public function setCategory($category) {
    $this->category = $category;
  }

  public function getCategory() {
    return $this->category;
  }

  public function setVendor($vendor) {
    $this->vendor = $vendor;
  }

  public function getVendor() {
    return $this->vendor;
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
}
