<?php

namespace Schema;

use GraphQL\GraphQL;
use Schema\Definition\Field;
use Schema\Definition\ObjectType;
use Schema\Definition\Schema;
use Schema\GraphQL\SchemaFactory;
use Schema\Handlers\Handler;

class Dispatcher extends \Phalcon\Mvc\User\Plugin
{
    protected $defaultNamespace;

    public function setDefaultNamespace($namespace)
    {
        $this->defaultNamespace = rtrim($namespace, '\\');
    }

    public function createHandler(ObjectType $objectType)
    {
        $handler = null;
        $handlerClassName = $objectType->getHandler();

        if ($handlerClassName) {

            $handler = new $handlerClassName;

        } else {

            $handlerClassName = $this->defaultNamespace . '\\' . $objectType->getName() . 'Handler';

            if (!class_exists($handlerClassName)) {
                $handlerClassName = Handler::class;
            }

            $handler = new $handlerClassName;
        }

        if ($handler instanceof \Phalcon\Di\Injectable) {
            $handler->setDI($this->di);
        }

        return $handler;
    }

    public function createResolver($handler, Field $field)
    {
        return function ($source, $args, $context, $info) use ($handler, $field) {

            $resolvers = $field->getResolvers();

            $fieldName = $field->getName();

            if (empty($resolvers)) {
                return $handler->$fieldName($source, $args, $context, $info);
            }

            foreach ($resolvers as $resolverFn) {

                if (is_callable($resolverFn)) {

                    $source = call_user_func($resolverFn, $source, $args, $context, $info);
                }
                else if (is_string($resolverFn)) {

                    $parts = explode('::', $resolverFn);

                    if (count($parts) === 2) {

                        $className = $parts[0];
                        $methodName = $parts[1];

                        $obj = new $className;
                        $source = $obj->$methodName($source, $args, $context, $info);
                    }
                    else {

                        $source = $handler->$resolverFn($source, $args, $context, $info);
                    }
                }
            }

            return $source;
        };
    }

    public function dispatch(Schema $schema)
    {
        $graphqlSchema = SchemaFactory::build($this, $schema);

        $request = $this->di->get('request');
        $response = $this->di->get('response');

        if ($request->getHeader('content-type') === 'application/json') {
            $data = $request->getJsonRawBody(true);
        } else {
            $data = $request->getQuery();
        }

        $requestString = isset($data['query']) && !empty($data['query']) ? $data['query'] : null;
        $operationName = isset($data['operation']) && !empty($data['operation']) ? $data['operation'] : null;
        $variableValues = isset($data['variables']) && !empty($data['variables']) ? $data['variables'] : null;

        try {

            $result = GraphQL::execute(
                $graphqlSchema,
                $requestString,
                null, // rootValue
                null, // context
                $variableValues,
                $operationName
            );

        } catch (\Exception $exception) {

            $result = [
                'errors' => [
                    ['message' => $exception->getMessage()]
                ]
            ];
        } finally {

            $response->setJsonContent($result);
            $response->send();
        }
    }
}
