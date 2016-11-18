<?php
namespace AppBundle\Entity;

use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="RefreshTokens")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RefreshTokenRepository")
 */
class RefreshToken extends BaseRefreshToken
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	*/
  protected $id;

	/**
		* @ORM\ManyToOne(targetEntity="Client")
		* @ORM\JoinColumn(nullable=false)
	*/
  protected $client;

	/**
		* @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
	*/
  protected $user;
}
