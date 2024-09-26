<?php

namespace Pantono\Pusher\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Pantono\Authentication\Model\User;

class PusherAuthEvent extends Event
{
    private User $user;
    private string $resourceType;
    private string $socketId;
    private string $resourceName;
    private array $authInfo;
    private bool $authorised = false;
    private string|int|null $resourceId;

    public function __construct(User $user, string $socketId, string $resourceType, string $resourceName, string|int|null $resourceId, array $authInfo = [])
    {
        $this->user = $user;
        $this->socketId = $socketId;
        $this->resourceType = $resourceType;
        $this->resourceName = $resourceName;
        $this->resourceId = $resourceId;
        $this->authInfo = $authInfo;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getSocketId(): string
    {
        return $this->socketId;
    }

    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    /**
     * @return array<mixed>
     */
    public function getAuthInfo(): array
    {
        return $this->authInfo;
    }

    public function getResourceId(): int|string|null
    {
        return $this->resourceId;
    }

    public function isAuthorised(): bool
    {
        return $this->authorised;
    }

    public function setAuthorised(bool $authorised): void
    {
        $this->authorised = $authorised;
    }
}
