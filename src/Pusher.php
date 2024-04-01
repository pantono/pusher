<?php

namespace Pantono\Pusher;

use Pusher\Pusher as PusherService;
use Pantono\Authentication\Model\User;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pantono\Pusher\Event\PusherAuthEvent;
use Pantono\Pusher\Repository\PusherRepository;

class Pusher
{
    private PusherService $pusher;
    private EventDispatcher $dispatcher;
    private PusherRepository $repository;

    public function __construct(PusherService $pusher, EventDispatcher $dispatcher, PusherRepository $repository)
    {
        $this->pusher = $pusher;
        $this->dispatcher = $dispatcher;
        $this->repository = $repository;
    }

    public function getPresenceAuth(string $channel, string $socket, string $userId, array $userInfo = []): string
    {
        return $this->pusher->authorizePresenceChannel($channel, $socket, $userId, $userInfo);
    }

    public function getSocketAuth(string $channel, string $socket, string|array|null $customData = null): string
    {
        if (is_array($customData)) {
            $customData = json_encode($customData, JSON_THROW_ON_ERROR);
        }
        return $this->pusher->authorizeChannel($channel, $socket, $customData);
    }

    public function sendToChannel(string $channel, string $event, array $data = []): object
    {
        return $this->pusher->trigger($channel, $event, $data);
    }

    public function processUserAuth(User $user, string $socketId, string $channelName, array $authInfo = []): ?string
    {
        [$type, $name, $id] = explode('-', $channelName, 3);
        $event = new PusherAuthEvent($user, $socketId, $type, $name, $id, $authInfo);
        $this->dispatcher->dispatch($event);
        if ($event->isAuthorised()) {
            if ($type === 'presence') {
                return json_decode($this->getPresenceAuth($channelName, $socketId, (string)$user->getId(), $event->getAuthInfo()), true, 512, JSON_THROW_ON_ERROR);
            }
            if ($type === 'private') {
                return json_decode($this->getSocketAuth($channelName, $socketId, $event->getAuthInfo()), true, 512, JSON_THROW_ON_ERROR);
            }
        }
        return null;
    }

    public function getPermissionForType(string $type): ?string
    {
        return $this->repository->getPermissionForType($type);
    }
}
