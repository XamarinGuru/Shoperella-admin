<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Deal;
use AppBundle\Form\DealType;
use Doctrine\ORM\Query;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;

class DealsController extends FOSRestController
{
  public function postDealAction($vendorId, Request $request) {
    $deal = new Deal();
    $vendor = $this->getDoctrine()->getRepository('AppBundle:Vendor')->find($vendorId);
    $deal->setVendor($vendor);
    $deal->setTitle($request->request->get("title"));
    $deal->setCaption($request->request->get("caption"));
    $deal->setDescription($request->request->get("description"));
    $deal->setExpiresAt($request->request->get("expiresAt"));
    if ($deal->getTitle() !== "") {
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($deal);
      $entityManager->flush();
      return View::create($deal);
    } else {
      return View::create($deal, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
//    $deal = new Deal();
//    $vendor = $this->fetchVendor($vendorId);
//    $deal->setVendor($vendor);
//    $this->denyAccessUnlessGranted('create', $deal);
//    $form = $this->createForm(DealType::class, $deal);
//    $form->handleRequest($request);
//    if ($form->isValid()) {
//      $entityManager = $this->getDoctrine()->getManager();
//      $entityManager->persist($deal);
//      $entityManager->flush();
//      return View::create($deal);
//    } else {
//      return View::create($form, Response::HTTP_UNPROCESSABLE_ENTITY);
//    }
  }

  /**
   * @Get("/deals")
   */
  public function getDealsAction()
  {
    $deals = $this->getDoctrine()->getManager()
              ->createQuery("SELECT d, v
                              FROM AppBundle:Deal d
                              JOIN d.vendor v
                              WHERE d.deleted != 1
                              AND d.expiresAt > :time")
              ->setParameter("time", new \DateTime('now'))
              ->getResult(Query::HYDRATE_ARRAY);

    return View::create($deals);
  }

  /**
   * @Get("/deals/search/{search}")
   */
  public function getDealsSearchAction($search)
  {
    $deals = $this->getDoctrine()->getManager()
                  ->createQuery("SELECT d, v
                              FROM AppBundle:Deal d
                              JOIN d.vendor v
                              WHERE d.deleted != 1
                              AND d.expiresAt > :time
                              AND (
                                    d.title LIKE :search 
                                    OR d.caption LIKE :search
                                    OR d.description LIKE :search)
                              ")
                  ->setParameter("time", new \DateTime("now"))
                  ->setParameter("search", "%" . $search . "%")
                  ->getResult(Query::HYDRATE_ARRAY);

    return View::create($deals);
  }

  /**
   * @Post("/deal/renew")
   */
  public function renewDealAction(Request $request)
  {
    $dealId    = $request->request->get("dealId");
    $vendorId  = $request->request->get("vendorId");
    $expiresAt = $request->request->get("expiresAt");

    $deal = $this->getDoctrine()->getRepository("AppBundle:Deal")->find($dealId);

    if (is_numeric($dealId) && is_numeric($vendorId) && $expiresAt != "") {

      $vendor = $this->getDoctrine()->getRepository("AppBundle:Vendor")->find($vendorId);

      if ($vendor == $deal->getVendor()) {
        $deal->setExpiresAt($request->request->get("expiresAt"));
        return View::create($deal);
      }
    }

    return View::create($deal, Response::HTTP_UNPROCESSABLE_ENTITY);
  }

  private function fetchVendor($vendorId) {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Vendor');
    return $repo->find($vendorId);
  }

  public function getDealsVendorAction(Request $request)
  {
    $vendorID = $request->query->get("vendorID");
    $deals    = $this->getDoctrine()->getManager()
                      ->createQuery("SELECT d FROM AppBundle:Deal WHERE d.vendor=:vendor AND d.deleted = 0")
                      ->setParameter("vendor", $vendorID)
                      ->getResult(Query::HYDRATE_OBJECT);
    return View::create($deals);
  }

  /**
   * @Post("/deal/delete")
   */
  public function deleteDealAction(Request $request)
  {
    $deal   = $this->getDoctrine()->getRepository("AppBundle:Deal")->find($request->request->get("dealId"));
    $vendor = $this->getDoctrine()->getRepository("AppBundle:Vendor")->find($request->request->get("vendorId"));

    if ($deal->getVendor() == $vendor && $vendor->getUser() == $this->getUser()) {
      $deal->setDeleted(true);

      return View::create($deal);
    } else {
      return View::create($deal, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  /**
   * @Post("/favorite/add")
   */
  public function postAddFavoriteAction(Request $request)
  {
    $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser()->getId());
    $deal = $this->getDoctrine()->getRepository("AppBundle:Deal")->find($request->request->get("dealId"));
    $user->addFavorite($deal);

    $this->getDoctrine()->getManager()->flush();

    return View::create($user);
  }

  /**
   * @Post("/favorite/remove")
   */
  public function postRemoveFavoriteAction(Request $request)
  {
    $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser()->getId());
    $deal = $this->getDoctrine()->getRepository("AppBundle:Deal")->find($request->request->get("dealId"));
    $user->removeFavorite($deal);

    $this->getDoctrine()->getManager()->flush();

    return View::create($user);
  }

  /**
   * @Get("/favorites")
   */
  public function getFavoritesAction()
  {
    $favorites = $this->getDoctrine()->getManager()
        ->createQuery("SELECT u, f, v
                            FROM AppBundle:User u
                            JOIN u.favorite f
                            JOIN f.vendor v
                            WHERE u.id = :user
                     ")
        ->setParameter("user", $this->getUser()->getId())
        ->getResult(Query::HYDRATE_ARRAY);

    return View::create($favorites);
  }

}
?>
