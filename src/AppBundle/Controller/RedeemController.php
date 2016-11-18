<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Redeemed;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;

class RedeemController extends FOSRestController {

    /**
     * @Post("/redeem/deal")
     */
    public function postRedeemDealAction(Request $request)
    {
        $deal = $this->getDoctrine()->getRepository("AppBundle:Deal")->find($request->request->get("dealId"));

        if ($deal->getExpiresAt()->getTimestamp() <= time())
        {
            //deal expired

            return View::create(array("status" => 0, "error" => 1, "msg" => "Expired"), Response::HTTP_NOT_FOUND);
        } else if ($deal->getDeleted() == 1) {
            //deal deleted

            return View::create(array("status" => 0, "error" => 2, "msg" => "Deleted"), Response::HTTP_NOT_FOUND);
        }

        $redeem = new Redeemed();
        $redeem->setDeal($deal);
        $redeem->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($redeem);
        $em->flush();

        return View::create($redeem);
    }

    /**
     * @Post("/redeem/offer")
     */
    public function postRedeemOfferAction(Request $request)
    {
        $offer = $this->getDoctrine()->getRepository("AppBundle:Offer")->find($request->request->get("offerId"));

        if ($offer->getExpiresAt()->getTimestamp() <= time())
        {
            //deal expired
            return View::create(array("status" => 0, "error" => 1, "msg" => "Expired", "expdate" => $offer->getExpiresAt()), Response::HTTP_NOT_FOUND);
        } else if ($offer->getDeletedAt() !== null) {
            //deal deleted

            return View::create(array("status" => 0, "error" => 2, "msg" => "Deleted"), Response::HTTP_NOT_FOUND);
        } else if ($offer->getUser() != $this->getUser()) {
            return View::create(array("status" => 0, "error" => 3, "msg" => "Wrong User"), Response::HTTP_NOT_FOUND);
        }

        $redeem = new Redeemed();
        $redeem->setOffer($offer);
        $redeem->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($redeem);
        $em->flush();

        $offer->setRedeemed($redeem);
        $em->flush();

        return View::create($redeem);
    }
}