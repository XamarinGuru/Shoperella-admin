<?php
namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ORM\EntityManager;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
  private $entityManager;

  public function __construct(EntityManager $entityManager) {
    $this->entityManager = $entityManager;
  }

  public function getCredentials(Request $request) {
    if (!$token=$request->headers->get('X-AUTH-TOKEN')) {
      return;
    }
    return ['token' => $token];
  }

  public function getUser($credentials, UserProviderInterface $userProvider) {
    $apiToken = $credentials['token'];
    return $this->entityManager->getRepository('AppBundle:User')
                ->findOneBy(['apiToken' => $apiToken]);
  }

  public function checkCredentials($credentials, UserInterface $user) {
    // credential checking unnecessary for token, auth succeeds
    return true;
  }

  public function onAuthenticationSuccess(Request $request,
                                          TokenInterface $token, 
                                          $providerKey) {
    // DO NOTHING
  }

  public function onAuthenticationFailure(Request $request,
                                          AuthenticationException $exception) {
    $data = ['message' => strtr($exception->getMessageKey(),
                                $exception->getMessageData())];
    return new JsonResponse($data, 403);
  }

  public function start(Request $request,
                        AuthenticationException $authException=null) {
    $data = ['message' => 'Authentication Required'];
    return new JsonResponse($data, 401);
  }

  public function supportsRememberMe() {
    return false;
  }
}
?>
