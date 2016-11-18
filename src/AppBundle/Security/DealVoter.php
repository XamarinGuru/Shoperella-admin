<?php
namespace AppBundle\Security;

use AppBundle\Entity\Deal;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class DealVoter extends Voter
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
    if (!$subject instanceof Deal) {
      return false;
    }
    return true;
  }

  protected function voteOnAttribute($attribute, $deal,
                                     TokenInterface $token) {
    $user = $token->getUser();
    if (!$user instanceof User) {
      // not logged in
      return false;
    }
    switch ($attribute) {
      case self::CREATE:
        return $this->canCreate($deal, $user);
      // TODO more actions will likely go here
    }
    throw new \LogicException('Vote attribute ' . $attribute . ' for Deals ' .
                              'does not exist.');
  }

  private function canCreate(Deal $deal, User $user) {
    $vendor = $this->entityManager->getRepository('AppBundle:Vendor')
                   ->find($deal->getVendor());
    $isOwner = false;
    if ($vendor) {
      $isOwner = $vendor->getOwner() == $user;
    }
    return $isOwner;
  }
}
?>
