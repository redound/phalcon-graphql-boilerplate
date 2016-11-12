<?php

namespace Schema;

use GraphQL\GraphQL;
use Schema\Definition\Schema;
use Schema\GraphQL\SchemaFactory;

class Dispatcher extends \Phalcon\Mvc\User\Plugin
{
    protected $defaultNamespace;

    public function setDefaultNamespace($namespace)
    {
        $this->defaultNamespace = rtrim($namespace, '\\');
    }

    public function dispatch(Schema $schema)
    {
        $graphqlSchema =  SchemaFactory::build($this->di, $this->defaultNamespace, $schema);

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
