<?php

namespace AdrianTanase\VectorStore\Test;

use AdrianTanase\VectorStore\Enums\VectorStoreProviderType;
use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    protected function getEnvironmentSetUp($app)
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../', '.env.testing');
        $dotenv->load();

        $app['config']->set('vector-store.default', VectorStoreProviderType::PINECONE->value);
        $app['config']->set('vector-store.pinecone_api_key', env('VECTOR_STORE_PINECONE_API_KEY', ''));
        $app['config']->set('vector-store.pinecone_environment', env('VECTOR_STORE_PINECONE_ENVIRONMENT', ''));

        $app['config']->set('vector-store.weaviate_url', env('VECTOR_STORE_WEAVIATE_URL', ''));
        $app['config']->set('vector-store.weaviate_api_key', env('VECTOR_STORE_WEAVIATE_API_KEY', ''));
    }
}
