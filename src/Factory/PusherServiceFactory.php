<?php

namespace Pantono\Pusher\Factory;

use Pantono\Contracts\Locator\FactoryInterface;
use Pusher\Pusher;

class PusherServiceFactory implements FactoryInterface
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function createInstance(): Pusher
    {
        $config = $this->config;

        return new Pusher($config['key'], $config['secret'], $config['app_id'], ['cluster' => $config['cluster'], 'host' => $config['host']]);
    }
}
