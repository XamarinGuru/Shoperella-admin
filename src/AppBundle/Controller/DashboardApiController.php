<?php
namespace AppBundle\Controller;

use Doctrine\ORM\Query;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Deal;
use FOS\RestBundle\Controller\Annotations\Post;
use AppBundle\Form\DealType;
use AppBundle\Entity\Offer;
use AppBundle\Storage\S3Storage;

class DashboardApiController extends FOSRestController
{
    /**
     * @Get("/dashboard/api/getUnfulfilledWishes", name="getUnfulfilledWishes")
     */
    public function getUnfulfilledWishesAction()
    {
        $wishData = $this->getDoctrine()->getRepository('AppBundle:Wish')->findAll();

        return View::create($wishData);
    }

    /**
     * @Get("/dashboard/api/getVendors", name="getVendors")
     */
    public function getVendorsAction()
    {
        $vendorData = $this->getDoctrine()
                            ->getRepository("AppBundle:Vendor")->findAll();

        return View::create($vendorData);
    }

    /**
     * @Get("/dashboard/api/hideWish/{wishId}")
     */
    public function getHideWishAction($wishId)
    {
        $wish = $this->getDoctrine()->getRepository("AppBundle:Wish")->find($wishId);
        $wish->hide();

        return View::create($wish);
    }

//    /**
//     * @Get("/offers/vendor/redeemed/{vendorId}")
//     */
//    public function getVendorRedeemdedasAction($vendorId)
//    {
//        $totalRedeemed = $this->getDoctrine()
//                            ->getManager()
//                            ->createQuery("SELECT count(o.id) as total_redeemed
//                                            FROM AppBundle:Offer o
//                                            WHERE o.redeemedAt IS NOT NULL
//                                            AND o.vendor = :vendor")
//                            ->setParameter("vendor", $vendorId)
//                            ->getSingleResult(Query::HYDRATE_ARRAY);
//
//        return View::create($totalRedeemed);
//    }

//    /**
//     * @Get("/offers/user")
//     */
//    public function getOfferasdUserAction()
//    {
//        $time   = date("Y-m-d H:i:s", time());
//
//        $offers = $this->getDoctrine()->getManager()
//            ->createQuery("SELECT w, v
//                            FROM AppBundle:Offer w
//                            JOIN w.vendor v
//                            WHERE w.user = :user
//                            AND w.expiresAt >= :time
//                            AND (
//                                CASE
//                                WHEN w.deletedAt IS NOT NULL
//                                THEN w.deletedAt
//                                ELSE w.expiresAt
//                                END
//                            ) >= :time")
//            ->setParameter("user", 6)
//            ->setParameter("time", $time)
//            ->getResult(Query::HYDRATE_ARRAY);
//
//        return View::create($offers);
//    }
}