# Vector database store for Laravel

This package provides an implementation of multiple vector databases (e.g. [Pinecone.io](https://www.pinecone.io/)).

### Currently supports
* [Pinecone.io](https://www.pinecone.io/)

### Plans to implement
* [Weaviate](https://github.com/timkley/weaviate-php)
* [MySql](https://planetscale.com/blog/planetscale-is-bringing-vector-search-and-storage-to-mysql) - once it's ready

### Usage
Using the `VectorStore` facade, you can easily access any provider and execute operations.

```php
VectorStore::dataset('conversations')
            ->namespace('general')
            ->upsert(
                new PineconeUpsertRequest()
                    ->id(1)
                    ->values([
                        -0.002739503,
                        -0.01970483,
                        -0.011307885,
                        -0.011125952,
                        -0.023119587,
                        0.0016207852
                    ])
                    ->metadata([
                        'text' => 'Vector store is lit!'
                    ])
            )
```

The default provider is `Pinecone.io`, this can be easily switched using the facade `VectorStore::provider(VectorStoreProviderType::PINECONE)`, or directly in the `vector-store` config. A full list of providers is available in [VectorStoreProviderType](https://github.com/adrianmtanase/laravel-vector-store/src/Enums/VectorStoreProviderType).

### Work in progress!

This is currently a work in progress, v1 will be released once it's stable.