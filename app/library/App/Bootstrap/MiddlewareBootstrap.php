<?php

namespace App\Bootstrap;

use App\BootstrapInterface;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconApi\Api;
use PhalconApi\Middleware\AuthenticationMiddleware;
use PhalconApi\Middleware\CorsMiddleware;
use PhalconApi\Middleware\NotFoundMiddleware;
use PhalconApi\Middleware\OptionsResponseMiddleware;

class MiddlewareBootstrap implements BootstrapInterface
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        $api
            ->attach(new CorsMiddleware($config->cors->allowedOrigins->toArray()))
            ->attach(new OptionsResponseMiddleware)
            ->attach(new NotFoundMiddleware)
            ->attach(new AuthenticationMiddleware);
    }
}
