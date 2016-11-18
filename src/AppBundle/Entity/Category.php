<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="Categories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Category
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
   * @ORM\Column(name="Name", type="string")
   */
  private $name;

  /**
   * @ORM\Column(name="Slug", type="string")
   */
  private $slug;

  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

  public function setSlug($slug) {
    $this->slug = $slug;
  }

  public function getSlug($slug) {
    return $this->slug;
  }

  public function slugify() {
    $this->setSlug($this->generateSlug());
  }

  public function generateSlug() {
    $raw = $this->getName();
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $raw)));
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
