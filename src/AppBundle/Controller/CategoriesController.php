<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;

class CategoriesController extends FOSRestController
{
  public function getCategoryAction($id) {
    // TODO
  }

  public function postCategoryAction(Request $request) {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);
    $valid = false;
    if ($form->isValid()) {
      $found = $this->fetchCategoryBySlug($category->generateSlug());
      if (!$found) {
        $valid = true;
      }
    }
    if ($valid) {
      $category->slugify();
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($category);
      $entityManager->flush();
      return View::create($category);
    } else {
      return View::create($form, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
  }

  private function fetchCategoryBySlug($slug) {
    $entityManager = $this->getDoctrine()->getManager();
    $repo = $entityManager->getRepository('AppBundle:Category');
    return $repo->findOneBy(['slug' => $slug]);
  }
}
?>
