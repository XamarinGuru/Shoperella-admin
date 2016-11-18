<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Wish;
use AppBundle\Form\WishType;
use AppBundle\Entity\Offer;

class WishesController extends FOSRestController
{
  public function postWishAction(Request $request) {
    $wish = new Wish();
    $form = $this->createForm(WishType::class, $wish);
    $form->handleRequest($request);
    if ($form->isValid()) {
      $wish->setUser($this->getUser());
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($wish);
      $entityManager->flush();
      return View::create($wish);
    } else {
      return View::create($form, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  public function postWishVendorAddAction(Request $request)
  {
    $vendorID = $request->request->get("vendorID");
    $wishID   = $request->request->get("wishID");
    $em = $this->getDoctrine()->getManager();

    if (is_numeric($wishID) && is_numeric($vendorID)) {

      $vendor = $this->getDoctrine()->getRepository("AppBundle:Vendor")->find($vendorID);
      $wish = $this->getDoctrine()->getRepository("AppBundle:Wish")->find($wishID);
      $wish->addVendor($vendor);
      $em->flush();

      if ($vendor->getAutoOfferStatus() == 1)
      {
        $autoResponseOffer = $vendor->getActiveAutoResponseOffer();
        $offer = new Offer();
        $offer->setVendor($vendor);
        $offer->setWish($wish);
        $offer->setUser($wish->getUser());
        $offer->setExpiresAt($autoResponseOffer->getExpiresAt());
        $offer->setTitle($autoResponseOffer->getTitle());
        $offer->setCaption($autoResponseOffer->getCaption());
        $offer->setDescription($autoResponseOffer->getDescription());

        $em = $this->getDoctrine()->getManager();
        $em->persist($offer);
        $em->flush();

        $autoresponse = $offer->getId();
      } else {
        $autoresponse = 0;
      }

      $response = array(
          "code" => 200,
          "message" => "success",
          "autoresponse" => $autoresponse
      );
    } else {
      $response = array(
        "code" => 400,
        "error" => "WishID " . $wishID . " Or VendorID " . $vendorID . " are not numeric"
      );
    }

    return View::create($response);
  }

  public function postWishVendorRemoveAction(Request $request)
  {
    $vendorID = $request->request->get("vendorID");
    $wishID   = $request->request->get("wishID");

    if (is_numeric($wishID) && is_numeric($vendorID))
    {
      $vendor = $this->getDoctrine()->getRepository("AppBundle:Vendor")->find($vendorID);
      $wish   = $this->getDoctrine()->getRepository("AppBundle:Wish")->find($wishID);

      $wish->removeVendor($vendor);
      $this->getDoctrine()->getManager()->flush();

      $response = array(
          "code" => 200,
          "message" => "success"
      );
    } else {
      $response = array(
        "code" => 400,
          "error" => "WishID " . $wishID . " Or VendorID " . $vendorID . " are not numeric"
      );
    }

    return View::create($response);
  }

  public function getWishesUnfulfilledAction(Request $request) {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $wishes = $this->fetchUnfulfilledWishes();
    return View::create($wishes);
  }

  private function fetchUnfulfilledWishes() {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Wish');
    return $repo->findAll();
  }
}
?>
