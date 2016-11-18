<?php
namespace AppBundle\Controller;

use AppBundle\Entity\AutoResponseOffer;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Offer;
use AppBundle\Form\OfferType;
use FOS\RestBundle\Controller\Annotations\Get;
use Doctrine\ORM\Query;

class OffersController extends FOSRestController
{
  /**
   * @Post("/create/offer")
   */
  public function postOfferAction(Request $request)
  {
    $offer = new Offer();
    $vendor = $this->getDoctrine()->getRepository('AppBundle:Vendor')->find($request->request->get("vendorId"));
    $wish = $this->getDoctrine()->getRepository("AppBundle:Wish")->find($request->request->get("wishId"));
    $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser());

    $offer->setVendor($vendor);
    $offer->setWish($wish);
    $offer->setUser($user);
    $offer->setExpiresAt($request->request->get("expiresAt"));
    $offer->setTitle($request->request->get("title"));
    $offer->setCaption($request->request->get("caption"));
    $offer->setDescription($request->request->get("description"));

    $em = $this->getDoctrine()->getManager();
    $em->persist($offer);
    $em->flush();

    return View::create($offer);
//    $offer = new Offer();
//    $vendor = $this->fetchVendor($vendorId);
//    $offer->setVendor($vendor);
//    $this->denyAccessUnlessGranted('create', $offer);
//    $form = $this->createForm(OfferType::class, $offer);
//    $form->handleRequest($request);
//    if ($form->isValid()) {
//      $deal = $this->fetchDeal($form->get('deal')->getData());
//      $offer->setDeal($deal);
//      $wish = $this->fetchWish($form->get('wish')->getData());
//      $offer->setWish($wish);
//      $offer->setUser($wish->getUser());
//      $expiresAt = $deal->generateExpiresAt(new \DateTime('now'));
//      $offer->setExpiresAt($expiresAt);
//      $entityManager = $this->getDoctrine()->getManager();
//      $entityManager->persist($offer);
//      $entityManager->flush();
//      // use the offer id to fulfill the wish
//      $wish->setOffer($offer);
//      // update the wish with the new offer reference
//      $entityManager->flush();
//      return View::create($offer);
//    } else {
//      return View::create($form, Response::HTTP_UNPROCESSABLE_ENTITY);
//    }
  }

  public function putOfferRedeemAction($id, Request $request) {
    $offer = $this->fetchOffer($id);
    $response = new Response();
    if (!$offer) {
      $response->setStatusCode(Response::HTTP_NOT_FOUND);
      return $response;
    }
    $this->denyAccessUnlessGranted('update', $offer);
    $offer->redeem();
    if ($offer->isRedeemed()) {
      $this->getDoctrine()->getManager()->flush();
      return View::create($offer);
    } else {
      return View::create($offer, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  public function putOfferExtendAction($id, Request $request) {
    $offer = $this->fetchOffer($id);
    $response = new Response();
    if (!$offer) {
      return View::create($offer, Response::HTTP_NOT_FOUND);
    }
    $this->denyAccessUnlessGranted('update', $offer);
    if ($offer->isExtendable()) {
      $offer->extend();
      $this->getDoctrine()->getManager()->flush();
      return View::create($offer);
    } else {
      return View::create($offer, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  /**
   * @Get("/offers/user")
   */
  public function getOfferUserAction()
  {
    $time   = date("Y-m-d H:i:s", time());

    $offers = $this->getDoctrine()->getManager()
        ->createQuery("SELECT w, v
                            FROM AppBundle:Offer w
                            JOIN w.vendor v
                            WHERE w.user = :user
                            AND w.dismissed = 0
                            AND w.expiresAt >= :time
                            AND (
                                CASE
                                WHEN w.deletedAt IS NOT NULL
                                THEN w.deletedAt
                                ELSE w.expiresAt
                                END
                            ) >= :time")
        ->setParameter("user", $this->getUser()->getId())
        ->setParameter("time", $time)
        ->getResult(Query::HYDRATE_ARRAY);

    return View::create($offers);
  }

  /**
   * @Get("/offer/dismiss/{offerId}")
   */
  public function getDismissOfferAction($offerId)
  {
    $offer = $this->getDoctrine()->getRepository("AppBundle:Offer")->find($offerId);
    if ($offer->getWish()->getUser() == $this->getUser())
    {
      $offer->setDismissed(true);
      $offer->setDismissedAt();

      $this->getDoctrine()->getManager()->flush();
      return View::create($offer);
    } else {
      return View::create($offer, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  /**
   * @Get("/offers/vendor/{vendorId}")
   */
  public function getOfferVendorAction($vendorId)
  {
    $offers = $this->getDoctrine()->getManager()
        ->createQuery("SELECT w, u
                            FROM AppBundle:Offer w
                            JOIN w.user u
                            WHERE w.vendor = :vendorId
                            AND w.expiresAt >= :time
                            AND (
                                CASE
                                WHEN w.deletedAt IS NOT NULL
                                THEN w.deletedAt
                                ELSE w.expiresAt
                                END
                            ) >= :time
                            ORDER BY w.expiresAt ASC")
        ->setParameter("vendorId", $vendorId)
        ->setParameter("time", date("Y-m-d H:i:s", time()))
        ->getResult(Query::HYDRATE_ARRAY);

    return View::create($offers);
  }

  /**
   * @Get("/offers/vendor/redeemed/{vendorId}")
   */
  public function getVendorRedeemdedAction($vendorId)
  {
    $totalRedeemed = $this->getDoctrine()
        ->getManager()
        ->createQuery("SELECT count(o.id) as total_redeemed
                                            FROM AppBundle:Offer o
                                            WHERE o.redeemedAt IS NOT NULL
                                            AND o.vendor = :vendor")
        ->setParameter("vendor", $vendorId)
        ->getSingleResult(Query::HYDRATE_ARRAY);

    return View::create($totalRedeemed);
  }

  /**
   * @Get("/offers/count/vendor/{vendorId}")
   */
  public function getOfferCountForVendorAction($vendorId)
  {
    $count  = $this->getDoctrine()
        ->getManager()
        ->createQuery("SELECT count(o.id) As numOffers
                                        FROM AppBundle:Offer o
                                        WHERE o.vendor = :vendorId
                                        AND o.expiresAt >= :time
                                        AND (
                                            CASE
                                            WHEN o.deletedAt IS NOT NULL
                                            THEN o.deletedAt
                                            ELSE o.expiresAt
                                            END
                                        ) >= :time")
        ->setParameter("vendorId", $vendorId)
        ->setParameter("time", date("Y-m-d H:i:s", time()))
        ->getSingleResult(Query::HYDRATE_ARRAY);

    return View::create($count);
  }

  /**
   * @Get("/offers/count/user")
   */
  public function getOfferCountForUserAction()
  {
    $count  = $this->getDoctrine()
        ->getManager()
        ->createQuery("SELECT count(o.id) As numOffers
                                        FROM AppBundle:Offer o
                                        WHERE o.user = :user
                                        AND o.expiresAt >= :time
                                        AND (
                                            CASE
                                            WHEN o.deletedAt IS NOT NULL
                                            THEN o.deletedAt
                                            ELSE o.expiresAt
                                            END
                                        ) >= :time")
        ->setParameter("user", $this->getUser())
        ->setParameter("time", date("Y-m-d H:i:s", time()))
        ->getSingleResult(Query::HYDRATE_ARRAY);

    return View::create($count);
  }

  public function deleteOfferAction($id, Request $request) {
    $offer = $this->fetchOffer($id);
    if (!$offer) {
      return View::create($offer, Response::HTTP_NOT_FOUND);
    }
    $this->denyAccessUnlessGranted('update', $offer);
    $offer->softDelete();
    $this->getDoctrine()->getManager()->flush();
    return View::create($offer);
  }

  private function fetchOffer($offerId) {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Offer');
    return $repo->find($offerId);
  }

  private function fetchDeal($dealId) {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Deal');
    return $repo->find($dealId);
  }

  private function fetchWish($wishId) {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Wish');
    return $repo->find($wishId);
  }

  private function fetchVendor($vendorId) {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Vendor');
    return $repo->find($vendorId);
  }

}
?>
