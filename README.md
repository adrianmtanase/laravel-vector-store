# Vector database store for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/adrianmtanase/laravel-vector-store.svg?style=flat-square)](https://packagist.org/packages/adrianmtanase/laravel-vector-store)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/adrianmtanase/laravel-vector-store/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/adrianmtanase/laravel-vector-store/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/adrianmtanase/laravel-vector-store/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/adrianmtanase/laravel-vector-store/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/adrianmtanase/laravel-vector-store.svg?style=flat-square)](https://packagist.org/packages/adrianmtanase/laravel-vector-store)


This package provides an implementation of multiple vector databases (e.g. [Pinecone.io](https://www.pinecone.io/)).

## Support us

If this helped you, consider supporting my development over on [Patreon](https://patreon.com/AdrianTanase443) or on [Github](https://github.com/sponsors/adrianmtanase).

### Installation
`Requires PHP ^8.1`
```bash
composer require adrianmtanase/laravel-vector-store
```

### Currently supports
* [Pinecone.io](https://www.pinecone.io/)
* [Weaviate](https://github.com/timkley/weaviate-php)

### Plans to implement
* [MySql](https://planetscale.com/blog/planetscale-is-bringing-vector-search-and-storage-to-mysql) - once it's ready

### ❗ If you're coming from version 0.0.25 ❗
* Coming from version `0.0.25` you'll have to re-publish the config, as the Pinecone environment variable has been replaced with `pinecone_host`
```bash
php artisan vendor:publish
```

### Usage
Using the `VectorStore` facade, you can easily access any provider and execute operations.

![Pinecone indexes](documentation/pinecone_indexes.png "Pinecone indexes")

```php
VectorStore::instance()
           ->namespace('general')
           ->upsert(
               PineconeUpsertRequest::build()
                   ->id('1')
                   ->values([
                       -0.002739503,
                       -0.01970483,
                       -0.011307885,
                       -0.011125952,
                       -0.023119587,
                       0.0016207852,
                       -0.003981551,
                       -0.029249357,
                       0.00983842,
                       -0.023721369
                   ])
                   ->metadata([
                       'text' => 'Vector store is lit!'
                   ])
           );
```

The default provider is `Pinecone.io`, this can be easily switched using the facade `VectorStore::provider(VectorStoreProviderType::PINECONE)`, or directly in the `vector-store` [config](https://github.com/adrianmtanase/laravel-vector-store/blob/main/config/vector-store.php).

### Weaviate
As Weaviate runs through GraphQL, the query language is complex. There are several useful methods in `WeaviateQueryRequest` that will help you query data more efficiently. For example:

```php
VectorStore::provider(VectorStoreProviderType::WEAVIATE)
           ->instance()
           ->namespace('general')
           ->query(
               WeaviateQueryRequest::build()
                   ->vector([
                       -0.002739503,
                       -0.01970483,
                       -0.011307885,
                       -0.011125952,
                       -0.023119587,
                       0.0016207852,
                       -0.003981551,
                       -0.029249357,
                       0.00983842,
                       -0.023721369
                   ])
                   ->properties(['text'])
                   ->withId()
                   ->withParameters(WeaviateQueryParameters::build()->group('type: closest, force: 1'))
           );
```

As the system is complex, the package also supports a `rawQuery` form, or even getting access to the underlying client.

#### Weaviate raw query
```php
VectorStore::provider(VectorStoreProviderType::WEAVIATE)
           ->instance()
           ->namespace('general')
           ->rawQuery('
               {
                Get {
                  General(nearVector: {
                    vector: [
                        -0.002739503,
                       -0.01970483,
                       -0.011307885,
                       -0.011125952,
                       -0.023119587,
                       0.0016207852,
                       -0.003981551,
                       -0.029249357,
                       0.00983842,
                       -0.023721369
                    ]  
                  }) {
                    text
                  }
                }
            }
           ');
```

#### Underlying Weaviate client

```php
VectorStore::provider(VectorStoreProviderType::WEAVIATE)
           ->instance()
           ->client()
           ->batchDelete('general') 
```