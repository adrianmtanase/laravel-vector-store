<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Vector Store Provider
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default vector store used by the
    | framework. All available providers are declared in VectorStoreProviderType
    |
    */
    'default' => env('VECTOR_STORE_PROVIDER', \AdrianTanase\VectorStore\Enums\VectorStoreProviderType::PINECONE->value),

    /*
    |--------------------------------------------------------------------------
    | Pinecone
    |--------------------------------------------------------------------------
    |
    | A managed, cloud-native vector database with a simple API and
    | no infrastructure hassles.
    |
    */
    'pinecone_api_key' => env('VECTOR_STORE_PINECONE_API_KEY', ''),
    'pinecone_environment' => env('VECTOR_STORE_PINECONE_ENVIRONMENT', ''),

    /*
    |--------------------------------------------------------------------------
    | Weaviate
    |--------------------------------------------------------------------------
    |
    | Weaviate is an open source vector database that stores both objects
    | and vectors.
    |
    */
    'weaviate_url' => env('VECTOR_STORE_WEAVIATE_URL', ''),
    'weaviate_api_key' => env('VECTOR_STORE_WEAVIATE_API_KEY', ''),
];
