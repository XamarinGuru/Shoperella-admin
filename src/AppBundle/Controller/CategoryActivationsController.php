<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\CategoryActivation;
use AppBundle\Form\CategoryActivationType;

class CategoryActivationsController extends FOSRestController
{
  public function postCategoryActivationAction($vendorId, Request $request) {
    $categoryActivation = new CategoryActivation();
    $vendor = $this->fetchVendor($vendorId);
    $categoryActivation->setVendor($vendor);
    $this->denyAccessUnlessGranted('create', $categoryActivation);
    $form = $this->createForm(CategoryActivationType::class,
                              $categoryActivation);
    $form->handleRequest($request);
    if ($form->isValid()) {
      $category = $this->fetchCategory($form->get('category')->getData());
      $categoryActivation->setCategory($category);
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($categoryActivation);
      $entityManager->flush();
      return View::create($categoryActivation);
    } else {
      return View::create($form, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  private function fetchVendor($vendorId) {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Vendor');
    return $repo->find($vendorId);
  }

  private function fetchCategory($categoryId) {
    $repo = $this->getDoctrine()->getRepository('AppBundle:Category');
    return $repo->find($categoryId);
  }
}
?>
