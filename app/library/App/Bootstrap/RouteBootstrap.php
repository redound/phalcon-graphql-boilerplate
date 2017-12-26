<?php

namespace App\Bootstrap;

use App\Auth\EmailAccountType;
use App\BootstrapInterface;
use App\Constants\Services;
use App\Model\User;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconApi\Api;
use PhalconApi\Http\Request;
use PhalconApi\Auth\Manager as AuthManager;

class RouteBootstrap implements BootstrapInterface
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        $api->post('/graphql', function() use ($di, $config) {

            $dispatcher = $di->get(Services::GRAPHQL_DISPATCHER);
            $graphqlSchema = $di->get(Services::GRAPHQL_SCHEMA);
            $schema = $di->get(Services::SCHEMA);

            return $dispatcher->dispatch($schema, $graphqlSchema, $config->debug);
        });

        $api->post('/access_token', function() use ($di) {

            /** @var AuthManager $authManager */
            $authManager = $di->get(Services::AUTH_MANAGER);

            /** @var Request $request */
            $request = $di->get(Services::REQUEST);

            $username = $request->getUsername();
            $password = $request->getPassword();

            $session = $authManager->loginWithUsernamePassword(EmailAccountType::NAME, $username, $password);

            /** @var User $user */
            $user = User::findFirst($session->getIdentity());

            $userResponse = [
                'id' => $user->id,
                'email' => $user->email
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

        $api->get('/', function() use ($api, $config) {

            return $config->application->title;
        });
    }
}
