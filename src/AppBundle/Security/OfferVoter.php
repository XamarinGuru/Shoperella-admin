<?php
namespace AppBundle\Security;

use AppBundle\Entity\Offer;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OfferVoter extends Voter
{
  const CREATE = 'create';
  const UPDATE = 'update';

  private $entityManager;

  public function __construct(EntityManager $entityManager) {
    $this->entityManager = $entityManager;
  }

  protected function supports($attribute, $subject) {
    if (!in_array($attribute, [self::CREATE, self::UPDATE])) {
      return false;
    }
    if (!$subject instanceof Offer) {
      return false;
    }
    return true;
  }

  protected function voteOnAttribute($attribute, $offer,
                                     TokenInterface $token) {
    $user = $token->getUser();
    if (!$user instanceof User) {
      // not logged in
      return false;
    }
    switch ($attribute) {
      case self::CREATE:
        return $this->canCreate($offer, $user);
      case self::UPDATE:
        return $this->canUpdate($offer, $user);
      // TODO more actions will likely go here
    }
    throw new \LogicException('Vote attribute ' . $attribute . ' for Offers ' .
                              'does not exist.');
  }

  private function canCreate(Offer $offer, User $user) {
    $vendor = $this->entityManager->getRepository('AppBundle:Vendor')
                   ->find($offer->getVendor());
    $isOwner = false;
    if ($vendor) {
      $isOwner = $vendor->getOwner() == $user;
    }
    return $isOwner;
  }

  private function canUpdate(Offer $offer, User $user) {
    return $offer->getUser() == $user;
  }
}
?>
