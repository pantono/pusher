<?php

namespace Pantono\Pusher\Endpoint;

use Pantono\Core\Router\Endpoint\AbstractEndpoint;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use League\Fractal\Resource\ResourceAbstract;
use Pantono\Pusher\Pusher;
use Pantono\Authentication\Model\User;
use Pantono\Authentication\Exception\AccessDeniedException;

class PusherAuth extends AbstractEndpoint
{
    private Pusher $pusher;

    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }

    public function processRequest(ParameterBag $parameters): array|ResourceAbstract|Response
    {
        /**
         * @var ?User $user
         */
        $user = $this->getSession()->get('user');
        if (!$user) {
            throw new AccessDeniedException('You must be logged in');
        }
        $results = [];
        foreach ($parameters->get('channel_name') as $channel) {
            $auth = $this->pusher->processUserAuth($user, $parameters->get('socket_id'), $channel);
            if ($auth === false) {
                $results[$channel] = ['status' => 403];
            } else {
                $results[$channel] = [
                    'status' => 200,
                    'data' => $auth
                ];
            }
        }
        return $results;
    }

}
