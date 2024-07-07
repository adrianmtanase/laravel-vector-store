<?php

namespace AdrianTanase\VectorStore\Facades;

use AdrianTanase\VectorStore\Abstracts\DatabaseAdapterAbstract;
use AdrianTanase\VectorStore\DatabaseAdapter;
use AdrianTanase\VectorStore\Enums\VectorStoreProviderType;
use Illuminate\Support\Facades\Facade;

/**
 * @method static DatabaseAdapter provider(VectorStoreProviderType $provider)
 * @method static DatabaseAdapterAbstract instance()
 * @method static DatabaseAdapterAbstract dataset()
 *
 * @see \AdrianTanase\VectorStore\DatabaseAdapter
 */
class VectorStore extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DatabaseAdapter::class;
    }
}
