<?php
namespace AppBundle\Model;

use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class MobileSignup
{
  public $errors = [];

  /**
   * @Assert\NotBlank()
   */
  protected $facebookAccessToken;

  private $orm;

  public function __construct($orm) {
    $this->orm = $orm;
  }

  public function create() {
    $this->errors = [];
    $credentials = ['app_id' => getenv('FACEBOOK_APP_ID'),
                    'app_secret' => getenv('FACEBOOK_APP_SECRET'),
                    'default_access_token' => $this->facebookAccessToken];
    $fb = new \Facebook\Facebook($credentials);
    try {
      $response = $fb->get('/me?fields=name,email');
    } catch(\Facebook\Facebook\Exceptions\FacebookResponseException $e) {
      $this->errors[] = 'Unable to fetch user from graph: ' . $e->getMessage();
    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
      $this->errors[] = 'Facebook SDK returned an error: ' . $e->getMessage();
    }
    if (!empty($this->errors)) {
      // Facebook request failed, stop trying
      return false;
    }
    $graphUser = $response->getGraphUser();
    $facebookId = $graphUser->getId();
    $user = $this->findUser($facebookId);
    if (!empty($user)) {
      $this->errors[] = 'User already exists';
      return;
    }
    $user = new User();
    $user->setName($graphUser->getName());
    $user->setFacebookId($facebookId);
    $user->setProfilePhotoUrl("https://graph.facebook.com/$facebookId/picture");
    $email = $graphUser->getEmail();
    $user->setEmail($email);
    $user->setToken();
    $manager = $this->orm->getManager();
    $manager->persist($user);
    $manager->flush();
    $this->user = $user;
    return $user->getId();
  }

  public function getFacebookAccessToken() {
    return $this->facebookAccessToken;
  }

  public function setFacebookAccessToken($facebookAccessToken) {
    $this->facebookAccessToken = $facebookAccessToken;
  }

  private function findUser($facebookId) {
    $repository = $this->orm->getRepository('AppBundle:User');
    return $repository->findByFacebookId($facebookId);
  }
}
?>
