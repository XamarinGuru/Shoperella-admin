<?php

namespace AppBundle\Notifications;

use RMS\PushNotificationsBundle\Message\AndroidMessage;
use RMS\PushNotificationsBundle\Message\iOSMessage;
use Symfony\Component\HttpFoundation\Response;

class PushNotification {

    private $rms_push_notifications;

    public function __construct($rms_push_notifications)
    {
        $this->rms_push_notifications = $rms_push_notifications;
    }

    public function sendPushNotification($message, $deviceIdentifier, $type)
    {
        if ($type == "ios"){
            $response = $this->iOSPushNotification($message, $deviceIdentifier);
        } else if($type == "android") {
            $response = $this->androidPushNotification($message, $deviceIdentifier);
        } else {
            $response = false;
        }

        return $response;
    }

    public function iOSPushNotification($message, $deviceIdentifier)
    {
        $message = new iOSMessage();
        $message->setMessage($message);
        $message->setDeviceIdentifier($deviceIdentifier);

        $response = $this->rms_push_notifications->send($message);

        return $response;
    }

    public function androidPushNotification($message, $deviceIdentifier)
    {
        $message = new AndroidMessage();
        $message->setGCM(true);
        $message->setMessage($message);

        $response = $this->rms_push_notifications->send($message);

        return $response;
    }

}