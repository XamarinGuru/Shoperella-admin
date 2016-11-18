<?php
namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="Users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
	/**
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
	protected $id;

  /**
   * @var string
   *
   * @ORM\Column(name="FacebookId", type="string", nullable=true)
   */
  protected $facebookId;

  /**
   * @var string
   *
   * @ORM\Column(name="Username", type="string")
   */
  protected $username;

  /**
   * @var string
   *
   * @ORM\Column(name="Password", type="string", nullable=true)
   */
  protected $password;

  /**
   * @var string
   *
   * @ORM\Column(name="Name", type="string")
   */
  protected $name;

  /**
   * @var string
   *
   * @ORM\Column(name="ProfilePhotoUrl", type="string", nullable=true)
   */
  protected $profilePhotoUrl;

  /**
   * @var string
   *
   * @ORM\Column(name="Email", type="string")
   */
  protected $email;

  /**
   * @var string
   *
   * @ORM\Column(name="ApiToken", type="string", nullable=true)
   */
  protected $apiToken;

	/**
   * @var datetime $created
   *
   * @ORM\Column(name="Created", type="datetime")
   */
  protected $created;

	/**
   * @var datetime $updated
   * 
   * @ORM\Column(name="Updated", type="datetime", nullable=true)
   */
  protected $updated;

  /**
   * @ORM\Column(name="Role", type="string", nullable=true)
   */
  private $role;

  /**
   * @ORM\ManyToMany(targetEntity="Deal")
   */
  private $favorite;

  /**
   * @ORM\Column(name="DeviceIdentifier", type="string", nullable=true)
   */
  private $deviceIdentifier;

  /**
   * @ORM\Column(name="DeviceType", type="string", nullable=true)
   */
  private $deviceType;

  /**
   * @ORM\Column(name="pushNotificationsEnabled", type="boolean")
   */
  private $pushNotificationsEnabled = false;

  /**
   * @ORM\OneToMany(targetEntity="Redeemed", mappedBy="user")
   */
  private $redemptions;

	/**
   * Gets triggered only on insert
   *	
   * @ORM\PrePersist
   */
  public function setCreated() {
    $this->created = new \DateTime("now");
	}

  public function getCreated() {
    return $this->created;
  }

	/**
   * Gets triggered every time on update
   *
   * @ORM\PreUpdate
   */
  public function setUpdated() {
    $this->updated = new \DateTime("now");
  }

  public function getUpdated() {
    return $this->updated;
  }

  public function setRole($role) {
    if ($role == 'admin') {
      $this->role = 'admin';
    }
  }

  public function getRole() {
    return $this->role;
  }

  public function setFacebookId($facebookId) {
    $this->facebookId = $facebookId;
  }

  public function getFacebookId() {
    return $this->facebookId;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

  public function setProfilePhotoUrl($profilePhotoUrl) {
    $this->profilePhotoUrl = $profilePhotoUrl;
  }

  public function getProfilePhotoUrl() {
    return $this->profilePhotoUrl;
  }

  public function setEmail($email) {
    $this->email = $email;
		// Let the username be the email address for now
		// XXX Will likely need to be reworked to handle form logins
		$this->username = $email;
  }

  public function getEmail() {
    return $this->email;
  }

  public function setToken() {
    $this->apiToken = $this->genToken();
  }

  public function getId() {
    return $this->id;
  }

	public function getUsername() {
		return $this->username;
	}

	public function getRoles() {
    if ($this->getRole() == 'admin') {
      return ['ROLE_USER', 'ROLE_ADMIN'];
    } else {
      return ['ROLE_USER'];
    }
	}

	public function getPassword() {
		// DO NOTHING (UNUSED)
	}

	public function getSalt() {
		// DO NOTHING (UNUSED)
	}

	public function eraseCredentials() {
		// DO NOTHING (UNUSED)
	}

  // generate a token
  private function genToken() {
    $length = 32;
    $bytes = openssl_random_pseudo_bytes($length * 2);
    $cleaned = str_replace(['/', '+', '='], '', base64_encode($bytes));
    $token = substr($cleaned, 0, $length);
    return $token;
  }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set apiToken
     *
     * @param string $apiToken
     *
     * @return User
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * Get apiToken
     *
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->favorite = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add favorite
     *
     * @param \AppBundle\Entity\Deal $favorite
     *
     * @return User
     */
    public function addFavorite(\AppBundle\Entity\Deal $favorite)
    {
        $this->favorite[] = $favorite;

        return $this;
    }

    /**
     * Remove favorite
     *
     * @param \AppBundle\Entity\Deal $favorite
     */
    public function removeFavorite(\AppBundle\Entity\Deal $favorite)
    {
        $this->favorite->removeElement($favorite);
    }

    /**
     * Get favorite
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavorite()
    {
        return $this->favorite;
    }

    /**
     * Set deviceIdentifier
     *
     * @param string $deviceIdentifier
     *
     * @return User
     */
    public function setDeviceIdentifier($deviceIdentifier)
    {
        $this->deviceIdentifier = $deviceIdentifier;

        return $this;
    }

    /**
     * Get deviceIdentifier
     *
     * @return string
     */
    public function getDeviceIdentifier()
    {
        return $this->deviceIdentifier;
    }

    /**
     * Set deviceType
     *
     * @param string $deviceType
     *
     * @return User
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    /**
     * Get deviceType
     *
     * @return string
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Set pushNotificationsEnabled
     *
     * @param boolean $pushNotificationsEnabled
     *
     * @return User
     */
    public function setPushNotificationsEnabled($pushNotificationsEnabled)
    {
        $this->pushNotificationsEnabled = $pushNotificationsEnabled;

        return $this;
    }

    /**
     * Get pushNotificationsEnabled
     *
     * @return boolean
     */
    public function getPushNotificationsEnabled()
    {
        return $this->pushNotificationsEnabled;
    }

    /**
     * Add redemption
     *
     * @param \AppBundle\Entity\Redeemed $redemption
     *
     * @return User
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
