<?php

namespace App\Bootstrap;

use App\Auth\UsernameAccountType;
use App\BootstrapInterface;
use App\Constants\Services;
use App\Constants\UserRoles;
use App\Model\User;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconApi\Api;
use PhalconApi\Http\Request;
use App\Auth\Manager as AuthManager;

class RouteBootstrap implements BootstrapInterface
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        $api->post('/graphql', function() use ($di) {

            $dispatcher = $di->get(Services::GRAPHQL_DISPATCHER);
            $schema = $di->get(Services::SCHEMA);

            return $dispatcher->dispatch($schema);
        });

        $api->post('/access_token', function() use ($di) {

            /** @var AuthManager $authManager */
            $authManager = $di->get(Services::AUTH_MANAGER);

            /** @var Request $request */
            $request = $di->get(Services::REQUEST);

            $username = $request->getUsername();
            $password = $request->getPassword();

            $session = $authManager->loginWithUsernamePassword(UsernameAccountType::NAME, $username, $password);

            /** @var User $user */
            $user = User::findFirst($session->getIdentity());

            $userResponse = [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role == UserRoles::ADMIN ? 'ADMIN' : 'USER'
            ];

            $response = [
                'token' => $session->getToken(),
                'expires' => $session->getExpirationTime(),
                'user' => $userResponse
            ];

            return [
                'data' => $response
            ];
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
