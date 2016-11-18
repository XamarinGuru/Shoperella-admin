<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Vendor;
use Doctrine\ORM\Query;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Offer;
use AppBundle\Entity\AutoResponseOffer;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function indexAction()
    {

        return $this->render('AppBundle:Dashboard:index.html.twig');
    }

    /**
     * @Route("/dashboard/api/addVendorToWish", name="db_add_vendor_to_wish")
     */
    public function addVendorToWish(Request $request)
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

                if ($autoResponseOffer->getExpiresAt()->getTimestamp() > time() ) {
                    $offer = new Offer();
                    $offer->setVendor($vendor);
                    $offer->setWish($wish);
                    $offer->setUser($wish->getUser());
                    $offer->setExpiresAt($autoResponseOffer->getExpiresAt()->format("Y-m-d H:i:s"));
                    $offer->setTitle($autoResponseOffer->getTitle());
                    $offer->setCaption($autoResponseOffer->getCaption());
                    $offer->setDescription($autoResponseOffer->getDescription());

                    $em->persist($offer);

                    $autoresponse = $offer->getId();
                } else {
                    $vendor->setAutoOfferStatus(0);
                    $autoresponse = 0;
                }
                $em->flush();
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

        return new JsonResponse($response);
    }

    /**
     * @Route("/dashboard/api/removeVendorFromWish", name="db_remove_vendor_from_wish")
     */
    public function removeVendorFromWish(Request $request)
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

        return new JsonResponse($response);
    }

    /**
     * List of all vendors
     * @Route("/dashboard/vendors", name="vendors")
     */
    public function vendorsListAction(){
        $vendors = $this->getDoctrine()->getRepository("AppBundle:Vendor")->findBy(array(), array("name"=>"ASC"));
        return $this->render("AppBundle:Dashboard/Vendors:list.html.twig", array("vendors" => $vendors));
    }

    /**
     * Add a Vendor
     * @Route("/dashboard/vendor/add", name="vendor_add")
     */
    public function vendorAddAction(){
        return $this->render("AppBundle:Dashboard/Vendors:add.html.twig");
    }

    /**
     * Add vendor form processing
     * @Route("/dashboard/vendor/save", name="vendor_save")
     */
    public function vendorSaveAction(Request $request){
        $vendor = new Vendor();
        $vendor->setName($request->get("name"))
            ->setAddress($request->get("address"))
            ->setCity($request->get("city"))
            ->setState($request->get("state"))
            ->setZip($request->get("zip"))
            ->setLatitude($request->get("latitude"))
            ->setLongitude($request->get("longitude"))
            ->setImagePath("");
        $this->getDoctrine()->getManager()->persist($vendor);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("vendors");
    }

    /**
     * List of all deals
     * @Route("/dashboard/deals", name="deals")
     */
    public function dealsListAction(){
        return $this->render("AppBundle:Dashboard/Deals:list.html.twig", array("deals" => null));
    }

    /**
     * @Route("/dashboard/test")
     * @Template("AppBundle::test.html.twig")
     */
    public function test()
    {
        return array();
    }
}
