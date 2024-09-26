<?php

namespace Pantono\Pusher\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Pantono\Pusher\Event\PusherAuthEvent;
use Pantono\Pusher\Pusher;

class ProcessPusherAuthentication implements EventSubscriberInterface
{
    private Pusher $pusher;

    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PusherAuthEvent::class => ['processPusherAuth']
        ];
    }

    public function processPusherAuth(PusherAuthEvent $event): void
    {
        $type = $this->pusher->getPermissionForType($event->getResourceType());
        if ($type) {
            if ($event->getUser()->hasPermission($type)) {
                $event->setAuthorised(true);
            }
        } else {
            $event->setAuthorised(true);
        }
    }
}
