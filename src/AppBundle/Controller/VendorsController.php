<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Vendor;
use AppBundle\Form\VendorType;
use Doctrine\ORM\Query;
use AppBundle\Storage\S3Storage;
use FOS\RestBundle\Controller\Annotations\Post;
use AppBundle\Entity\AutoResponseOffer;
use FOS\RestBundle\Controller\Annotations\Get;

class VendorsController extends FOSRestController
{
  public function getVendorAction($id) {
    $vendor = $this->fetchVendor($id);
    $repo = $this->getDoctrine()->getRepository('AppBundle:Deal');
    $deals = $repo->findBy(['vendor' => $id]);
    $dealsData = [];
    foreach ($deals as $deal) {
      $dealData = ['id' => $deal->getId(), 'vendor' => $deal->getVendor(),
                   'title' => $deal->getTitle(),
                   'caption' => $deal->getCaption(),
                   'description' => $deal->getDescription(),
                   'expires_at' => $deal->getExpiresAt(),
                   'hours_available' => $deal->getHoursAvailable(),
                   'daily_deal' => $deal->getDailyDeal()];
      $dealsData[] = $dealData;
    }
    $vendorData = ['id' => $vendor->getId(),
                   'name' => $vendor->getName(),
                   'street1' => $vendor->getStreet1(),
                   'street2' => $vendor->getStreet2(),
                   'city' => $vendor->getCity(),
                   'state' => $vendor->getState(),
                   'zip' => $vendor->getZip(),
                   'owner' => $vendor->getOwner(),
                   'image_path' => $vendor->getImagePath(),
                   'latitude' => $vendor->getLatitude(),
                   'longitude' => $vendor->getLongitude(),
                   'deals' => $dealsData];
    return View::create($vendorData);
  }

  public function getVendorsAction()
  {
    $vendorData = $this->getDoctrine()->getRepository("AppBundle:Vendor")->findAll();

    return View::create($vendorData);
  }

  public function getVendorsByCoordsAction(Request $request) {
    $coords = $request->query->get('coords');
    $parts = explode(',', $coords);
    $lat = $parts[0];
    $lng = $parts[1];
    $vendorData = $this->getDoctrine()->getRepository('AppBundle:Vendor')
        ->nearby($lat, $lng);
    return View::create($vendorData);
  }

  public function getVendorsByOwnerAction(Request $request)
  {
    $ownerID = $request->query->get("owner");
    $vendorData = $this->getDoctrine()
        ->getManager()
        ->createQuery('SELECT v FROM AppBundle:Vendor v WHERE v.owner = :Owner')
        ->setParameter('Owner', $ownerID)
        ->getResult(Query::HYDRATE_OBJECT);
    return View::create($vendorData);
  }

  public function getVendorsAndDealsAction(Request $request)
  {
    $coords     = $request->query->get('coords');
    $vendorData = $this->fetchVendors($coords);
    $converted  = array();
    foreach ($vendorData as $vendor) {
      $vendorId = $vendor->getId();
      $entityManager = $this->getDoctrine()->getManager();
      $query = $entityManager->createQuery('SELECT d FROM AppBundle:Deal d ' .
          'WHERE d.vendor = :id AND ' .
          'd.expiresAt > CURRENT_TIMESTAMP()')
          ->setParameter('id', $vendorId);
      $deals = $query->getResult();
      $vendor['deals'] = $deals;
      $converted[] = $vendor;
    }
    return View::create($converted);
  }

  public function postVendorAction(Request $request) {
    $vendor = new Vendor();

    $vendor->setName($request->request->get("name"));
    $vendor->setStreet1($request->request->get("street1"));
    $vendor->setStreet2($request->request->get("street2"));
    $vendor->setZip($request->request->get("zip"));

    if ($request->files->has("logo"))
    {
      $image  = $request->files->get('logo');
      $imagePath = $this->get("s3_storage")->PutFile($image->getClientOriginalName(), $image);
      $vendor->setImagePath($imagePath);
    }

    $vendor->setOwner($this->getUser());
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($vendor);

    $entityManager->flush();
    return View::create($vendor);
  }


  /**
   * @Post("/vendors/update/{vendorId}")
   */
  public function postUpdateVendorAction(Request $request, $vendorId)
  {
    $vendor = $this->getDoctrine()->getRepository("AppBundle:Vendor")->find($vendorId);

    if (is_numeric($vendorId) && $vendorId > 0) {
      $vendor->setName($request->request->get("name"));
      $vendor->setStreet1($request->request->get("street1"));
      $vendor->setStreet2($request->request->get("street2"));
      $vendor->setZip($request->request->get("zip"));

      if ($request->files->has("logo")) {
        $image = $request->files->get('logo');
        $imagePath = $this->get("s3_storage")->PutFile($image->getClientOriginalName(), $image);
        $vendor->setImagePath($imagePath);
      }

      $this->getDoctrine()->getManager()->flush();

      return View::create($vendor);
    } else {
      return View::create($vendor, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  public function putVendorAssignAction($vendorId, Request $request) {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $vendor = $this->fetchVendor($vendorId);
    $wishId = $request->get('wish');
    $wish = $this->fetchWish($wishId);
    $wish->setVendor($vendor);
    $this->getDoctrine()->getManager()->flush();
    // just respond OK
    return new Response();
  }

  private function fetchVendor($vendorId) {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Vendor');
    return $repo->find($vendorId);
  }

  private function fetchWish($wishId) {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Wish');
    return $repo->find($wishId);
  }

  public function getVendorAssignedWishesAction($vendorID = 0) {
    $wishes = $this->getDoctrine()->getManager()
        ->createQuery("SELECT w, u
                        FROM AppBundle:Wish w
                        JOIN w.user u
                        JOIN w.vendor v
                        WHERE v.id = :vendorID")
        ->setParameter("vendorID", $vendorID)
        ->getResult(Query::HYDRATE_ARRAY);
    return View::create($wishes);
  }

  /**
   * @Post("/vendor/add/logo")
   */
  public function postVendorImageAction(Request $request)
  {
    $image  = $request->files->get('logo');
    $vendorId = $request->request->get("vendorId");

    $vendor = $this->getDoctrine()
        ->getRepository("AppBundle:Vendor")
        ->find($vendorId);

    if ($vendor->getOwner() == $this->getUser()) {
      $imagePath = $this->get("s3_storage")->PutFile($image->getClientOriginalName(), $image);

      $vendor->setImagePath($imagePath);

      $this->getDoctrine()->getManager()->flush();

      return View::create($vendor);
    } else {
      return View::create($vendor, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  /**
   * @Post("/vendor/autoresponse")
   */
  public function createAutoResponseAction(Request $request)
  {
    $update = false;
    if ($request->request->get("update") == 1)
    {
      $update = true;
      $ar = $this->getDoctrine()->getRepository("AppBundle:AutoResponseOffer")->find($request->request->get("autoResponseId"));
      $vendor = $ar->getVendor();
    } else {
      $ar     = new AutoResponseOffer();
      $vendor = $this->getDoctrine()->getRepository("AppBundle:Vendor")->find($request->request->get("vendorId"));
      $ar->setVendor($vendor);
    }

    $vendor->setAutoOfferStatus(true);

    $ar->setTitle($request->request->get("title"));
    $ar->setCaption($request->request->get("caption"));
    $ar->setDescription($request->request->get("description"));
    $ar->setExpiresAt($request->request->get("expiresAt"));

    $em = $this->getDoctrine()->getManager();
    if ($update === false)
    {
      $em->persist($ar);
    }

    $vendor->setActiveAutoResponseOffer($ar);
    $em->flush();

    return View::create($ar);
  }

  /**
   * @Post("/vendor/autoresponse/delete")
   */
  public function deleteAutoResponseAction(Request $request)
  {
    $ar = $this->getDoctrine()->getRepository("AppBundle:AutoResponseOffer")->find($request->request->get("autoResponseId"));
    $ar->setDeleted(true);
    $ar->setDeletedAt();

    $vendor = $ar->getVendor();
    $vendor->setAutoOfferStatus(0);

    $em = $this->getDoctrine()->getManager();
    $em->flush();

    return View::create($ar);
  }
}
?>
