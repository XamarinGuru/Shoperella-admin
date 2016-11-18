<?php
namespace AppBundle\Security\User;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserProvider implements UserProviderInterface
{
  private $entityManager;

  public function __construct(EntityManager $entityManager) {
    $this->entityManager = $entityManager;
  }

  public function loadUserByUsername($username) {
    $repository = $this->entityManager->getRepository('AppBundle:User');
    $user = $repository->findByUsername($username);
    if ($user) {
      return $user;
    }
    $exceptionMessage = sprintf('Username "%s" does not exist.', $username);
    throw new UsernameNotFoundException($exceptionMessage);
  }

  public function refreshUser(UserInterface $user) {
    if ($user instanceof User) {
      return $this->loadUserByUsername($user->getUsername());
    }  
    $class = get_class($user);
    $exceptionMessage = sprintf('Instances of "%s" are not supported.', $class);
    throw new UnsupportedUserException($exceptionMessage);
  }

  public function supportsClass($class) {
    return $class === 'AppBundle\Entity\User';
  }
}
?>
