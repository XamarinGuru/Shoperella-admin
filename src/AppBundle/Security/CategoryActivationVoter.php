<?php
namespace AppBundle\Security;

use AppBundle\Entity\CategoryActivation;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CategoryActivationVoter extends Voter
{
  const CREATE = 'create';

  private $entityManager;

  public function __construct(EntityManager $entityManager) {
    $this->entityManager = $entityManager;
  }

  protected function supports($attribute, $subject) {
    if (!in_array($attribute, [self::CREATE])) {
      return false;
    }
    if (!$subject instanceof CategoryActivation) {
      return false;
    }
    return true;
  }

  protected function voteOnAttribute($attribute, $categoryActivation,
                                     TokenInterface $token) {
    $user = $token->getUser();
    if (!$user instanceof User) {
      // not logged in
      return false;
    }
    switch ($attribute) {
      case self::CREATE:
        return $this->canCreate($categoryActivation, $user);
    }
    throw new \LogicException('Vote attribute ' . $attribute .
                              ' for CategoryActivation does not exist.');
  }

  private function canCreate(CategoryActivation $categoryActivation,
                             User $user) {
    if ($vendor=$categoryActivation->getVendor()) {
      return $vendor->getOwner() == $user;
    }
    return false;
  }
}
?>
