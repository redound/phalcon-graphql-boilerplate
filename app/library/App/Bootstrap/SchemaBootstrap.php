<?php

namespace App\Bootstrap;

use App\BootstrapInterface;
use App\Collections\ProjectCollection;
use App\Collections\TicketCollection;
use App\Collections\UserCollection;
use App\Constants\AclRoles;
use App\Constants\Services;
use GraphQL\Utils\BuildSchema;
use Phalcon\Config;
use Phalcon\DiInterface;
use PhalconApi\Api;
use PhalconGraphQL\Definition\Fields\Field;
use PhalconGraphQL\Definition\ObjectType;
use PhalconGraphQL\Definition\Schema;
use PhalconGraphQL\GraphQL\DocumentFactory;
use PhalconGraphQL\Plugins\Authorization\AclAuthorizationPlugin;
use PhalconGraphQL\Plugins\Paging\OffsetLimitPagingPlugin;
use PhalconGraphQL\Plugins\Filtering\FilterPlugin;
use PhalconGraphQL\Plugins\Sorting\SimpleSortingPlugin;

class SchemaBootstrap implements BootstrapInterface
{
    public static function schema()
    {
        return Schema::factory()

            ->embedList()

            ->plugin(new AclAuthorizationPlugin)
            ->plugin(new FilterPlugin)
            ->plugin(new SimpleSortingPlugin)
            ->plugin(new OffsetLimitPagingPlugin)

            ->object(ObjectType::query()
                ->field(Field::viewer())
            )

            ->object(ObjectType::viewer())

            ->object(ObjectType::mutation())

            ->mount(new ProjectCollection)
            ->mount(new TicketCollection)
            ->mount(new UserCollection);
    }

    public function run(Api $api, DiInterface $di, Config $config)
    {
        $di->setShared(Services::SCHEMA, function() use ($di){

            /** @var \Phalcon\Cache\BackendInterface $schemaCache */
            $schemaCache = $di->get(Services::SCHEMA_CACHE);
            $cacheKey = 'schema';

            if($schemaCache->exists($cacheKey)){
                return $schemaCache->get($cacheKey);
            }

            $schema = SchemaBootstrap::schema();
            $schema->build($di);

            $schemaCache->save($cacheKey, $schema);

            return $schema;
        });

        $di->setShared(Services::GRAPHQL_SCHEMA, function() use ($di){

            /** @var \Phalcon\Cache\BackendInterface $schemaCache */
            $schemaCache = $di->get(Services::SCHEMA_CACHE);
            $schema = $di->get(Services::SCHEMA);

            $cacheKey = 'graphqlDocument';

            $document = null;

            if($schemaCache->exists($cacheKey)){

                $document = $schemaCache->get($cacheKey);
            }
            else {

                $document = DocumentFactory::build($schema);
                $schemaCache->save($cacheKey, $document);
            }

            return BuildSchema::build($document, DocumentFactory::createTypeConfigDecorator($schema));
        });
    }
}
