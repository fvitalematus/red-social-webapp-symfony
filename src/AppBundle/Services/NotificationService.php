<?php

namespace AppBundle\Services;

use BackendBundle\Entity\Notification;

class NotificationService {

    public $manager;

    public function __construct($manager) {
        $this->manager = $manager;
    }

    public function set($user, $type, $typeId, $extra = null) {
        $em = $this->manager;

        $notification = new Notification();
        $notification->setUser($user);
        $notification->setType($type);
        $notification->setTypeId($typeId);
        $notification->setReaded(0);
        $notification->setCreatedAt(new \DateTime("now"));
        $notification->setExtra($extra);

        $em->persist($notification);
        $flush = $em->$flush();

        if ($flush == null) {
            $status = true;
        } else {
            $status = false;
        }

        return $status;
    }

    public function read($user) {
        $em = $this->manager;

        $notification_repo = $em->getRepository('BackendBundle:Notification');
        $notifications = $notification_repo->findBy(array('user' => $user));

        foreach ($notifications as $notification) {
            $notification->setReaded(1);
            $em->persist($notification);
        }
        $flush = $em->flush();

        if ($flush == null) {
            return true;
        } else {
            return false;
        }

        return true;
    }

}
