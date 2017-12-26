<?php

namespace App\Bootstrap;

use App\Auth\EmailAccountType;
use Phalcon\Config;
use PhalconApi\Api;
use Phalcon\DiInterface;
use App\BootstrapInterface;
use App\Constants\Services;
use Phalcon\Mvc\Url as UrlResolver;
use App\User\Service as UserService;
use PhalconApi\Auth\Manager as AuthManager;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use PhalconApi\Auth\TokenParsers\JWTTokenParser;
use PhalconGraphQL\Dispatcher;

class ServiceBootstrap implements BootstrapInterface
{
    public function run(Api $api, DiInterface $di, Config $config)
    {
        /**
         * @description Config - \Phalcon\Config
         */
        $di->setShared(Services::CONFIG, $config);

        /**
         * @description Phalcon - \Phalcon\Db\Adapter\Pdo\Mysql
         */
        $di->set(Services::DB, function () use ($config, $di) {

            $dbConfig = $config->get('database')->toArray();
            $adapter = $dbConfig['adapter'];
            unset($dbConfig['adapter']);
            $class = 'Phalcon\Db\Adapter\Pdo\\' . $adapter;

            $connection = new $class($dbConfig);
            $connection->setEventsManager($di->get(Services::EVENTS_MANAGER));

            return $connection;
        });

        /**
         * @description Phalcon - \Phalcon\Mvc\Url
         */
        $di->set(Services::URL, function () use ($config) {

            $url = new UrlResolver;
            $url->setBaseUri($config->get('application')->baseUri);
            return $url;
        });

        /**
         * @description Phalcon - EventsManager
         */
        $di->setShared(Services::EVENTS_MANAGER, function () use ($di, $config) {

            return new EventsManager;
        });

        /**
         * @description Phalcon - \Phalcon\Mvc\Model\Manager
         */
        $di->setShared(Services::MODELS_MANAGER, function () use ($di) {

            $modelsManager = new ModelsManager;
            return $modelsManager->setEventsManager($di->get(Services::EVENTS_MANAGER));
        });

        /**
         * @description Phalcon - ModelsCache
         */
        $di->setShared(Services::MODELS_CACHE, function() use ($config) {

            // Use Memcached in production, else none
            if(!$config->cachingEnabled){

                return new \Phalcon\Cache\Backend\Memory(new \Phalcon\Cache\Frontend\None());
            }
            else {

                //Cache data for one hour by default
                $frontCache = new \Phalcon\Cache\Frontend\Data([
                    "lifetime" => 3600 * 24,
                    "prefix" => "models-"
                ]);

                return new \Phalcon\Cache\Backend\Redis($frontCache, $config->redis->toArray());
            }
        });

        /**
         * @description Phalcon - ModelsMetaData
         */
        $di->setShared(Services::MODELS_METADATA, function() use ($config) {

            // Use Memcache in production, else memory
            if(!$config->cachingEnabled){
                return new \Phalcon\Mvc\Model\Metadata\Memory();
            }
            else {
                return new \Phalcon\Mvc\Model\MetaData\Redis($config->redis->toArray());
            }
        });


        /**
         * @description PhalconGraphQL - TokenParsers
         */
        $di->setShared(Services::TOKEN_PARSER, function () use ($di, $config) {

            return new JWTTokenParser($config->get('authentication')->secret, JWTTokenParser::ALGORITHM_HS256);
        });

        /**
         * @description PhalconGraphQL - AuthManager
         */
        $di->setShared(Services::AUTH_MANAGER, function () use ($di, $config) {

            $authManager = new AuthManager($config->get('authentication')->expirationTime);
            $authManager->registerAccountType(EmailAccountType::NAME, new EmailAccountType);

            return $authManager;
        });

        /**
         * @description PhalconGraphQL - \PhalconApi\User\Service
         */
        $di->setShared(Services::USER_SERVICE, new UserService);

        /**
         * @description PhalconGraphQL - \Schema\Dispatcher
         */
        $di->setShared(Services::GRAPHQL_DISPATCHER, function () {

            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('App\Handlers');

            return $dispatcher;
        });

        /**
         * @description App - SchemaCache
         */
        $di->setShared(Services::SCHEMA_CACHE, function() use ($config) {

            // Use Memcached in production, else none
            if(!$config->cachingEnabled){

                return new \Phalcon\Cache\Backend\Memory(new \Phalcon\Cache\Frontend\None());
            }
            else {

                //Cache data for one hour by default
                $frontCache = new \Phalcon\Cache\Frontend\Data([
                    "lifetime" => 3600 * 24,
                    "prefix" => "schema-"
                ]);

                return new \Phalcon\Cache\Backend\Redis($frontCache, $config->redis->toArray());
            }
        });
    }
}
