<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class PingController extends FOSRestController
{
  public function pingAction() {
    $view = View::create();
    $view->setData(['pong']);
    return $view;
  }
}
?>
