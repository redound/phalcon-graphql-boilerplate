<?php

namespace App\Bootstrap;

use App\BootstrapInterface;
use App\Constants\Services;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconApi\Api;

class RouteBootstrap implements BootstrapInterface
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        $api->post('/', function() use ($di) {

            $dispatcher = $di->get(Services::GRAPHQL_DISPATCHER);
            $schema = $di->get(Services::SCHEMA);

            return $dispatcher->dispatch($schema);
        });

        $api->get('/', function() use ($api) {

            /** @var \Phalcon\Mvc\View\Simple $view */
            $view = $api->di->get(Services::VIEW);

            return $view->render('general/index');
        });

        $api->get('/proxy.html', function() use ($api, $config) {

            /** @var \Phalcon\Mvc\View\Simple $view */
            $view = $api->di->get(Services::VIEW);

            $view->setVar('client', $config->clientHostName);
            return $view->render('general/proxy');
        });
    }
}
