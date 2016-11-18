<?php
namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use AppBundle\Model\MobileSignup;
use AppBundle\Form\MobileSignupType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends FOSRestController
{
  /**
   * @Post("/auth/register")
   */
  public function postRegisterAction(Request $request) {
    $orm = $this->getDoctrine();
    $mobileSignup = new MobileSignup($orm);
    $form = $this->createForm(MobileSignupType::class, $mobileSignup);
    $form->handleRequest($request);
    if ($form->isValid()) {
      $mobileSignup->create();
      if (empty($mobileSignup->errors)) {
        return View::create($mobileSignup->user, Response::HTTP_CREATED);
      } else {
        $json = ['code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                 'message' => 'Mobile Signup Failed',
                 'errors' => ['errors' => $mobileSignup->errors]];
        return View::create($json, Response::HTTP_UNPROCESSABLE_ENTITY);
      }
    } else {
      $json = ['code' => Response::HTTP_BAD_REQUEST,
               'message' => 'Mobile Signup Failed',
               'errors' => ['errors' => ['facebookAuthToken required']]];
      return View::create($json, Response::HTTP_BAD_REQUEST);
    }
  }

  /**
   * @Post("/auth/login")
   */
  public function postAuthLoginAction(Request $request) {
    $facebookId = $request->get('facebookId');
    $user = $this->fetchUserByFacebookId($facebookId);
    if ($user) {
      $user->setToken();
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->flush();
      return View::create($user);
    } else {
      $response = new Response();
      $response->setStatusCode(Response::HTTP_NOT_FOUND);
      return $response;
    }
  }

  /**
   * @Get("/auth/session")
   */
  public function getAuthSessionAction(Request $request) {
    $user = $this->getUser();
    $repo = $this->getDoctrine()->getRepository('AppBundle:Vendor');
    $vendors = $repo->findBy(['owner' => $user->getId()]);
    $data = ['id' => $user->getId(), 'facebook_id' => $user->getFacebookId(),
             'username' => $user->getUsername(), 'name' => $user->getName(),
             'profile_photo_url' => $user->getProfilePhotoUrl(),
             'email' => $user->getEmail(), 'created' => $user->getCreated(),
             'updated' => $user->getUpdated(), 'vendors' => $vendors];
    return View::create($data);
  }

  private function fetchUserByFacebookId($facebookId) {
    $repo = $this->getDoctrine()->getRepository('AppBundle:User');
    return $repo->findOneBy(['facebookId' => $facebookId]);
  }

  /**
   * @Get("/auth/logout")
   */
  public function getUserLogoutAction()
  {
    $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser()->getId());
    $user->setApiToken("");

    $this->getDoctrine()->getManager()->flush();

    return View::create($user);
  }

  /**
   * @Post("/user")
   */
  public function postUserAction(Request $request)
  {
    $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser()->getId());

    $user->setEmail($request->request->get("email"));
    $user->setName($request->request->get("name"));

    $this->getDoctrine()->getManager()->flush();

    return View::create($user);
  }

  /**
   * @Get("/user")
   */
  public function getUserAction()
  {
    $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($this->getUser()->getId());
    return View::create($user);

  }

  /**
   * @Post("/user/notifications")
   */
  public function postUserNotificationsAction(Request $request)
  {
    $user = $this->getUser();
    $user->setPushNotificationsEnabled(true);
    $user->setDeviceType($request->request->get("deviceType"));
    $user->setDeviceIdentifier($request->request->get("deviceIdentifier"));

    $em = $this->getDoctrine()->getManager();

    $em->flush();

    return View::create($user);
  }

  /**
   * @Get("/user/sendTestNotification")
   */
  public function testNotificationAction()
  {
    $identifier = $this->getUser()->getDeviceIdentifier();
    $type       = $this->getUser()->getDeviceType();
    $message    = "Test Message";
    $response = $this->get("push_notifications")->sendPushNotification($message, $identifier, $type);

    return new JsonResponse(array("identifier" => $identifier, "type" => $type, "message" => $message, "response" => $response));
  }

  /**
   * @Get("/user/feedback")
   */
  public function feedbackAction()
  {
    $response = $this->get("rms_push_notifications.ios.feedback")->getDeviceUUIDs();

    return new JsonResponse($response);
  }
}
?>
