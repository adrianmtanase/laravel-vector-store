<?php

namespace AdrianTanase\VectorStore;

use AdrianTanase\VectorStore\Abstracts\DatabaseAdapterAbstract;
use AdrianTanase\VectorStore\Contracts\VectorStoreContract;
use AdrianTanase\VectorStore\Enums\VectorStoreProviderType;
use AdrianTanase\VectorStore\Exceptions\InvalidProviderException;
use AdrianTanase\VectorStore\Providers\Pinecone\Pinecone;
use AdrianTanase\VectorStore\Providers\Weaviate\Weaviate;
use Illuminate\Support\Facades\Config;

/**
 * @class DatabaseAdapter
 */
class DatabaseAdapter implements VectorStoreContract
{
    protected VectorStoreProviderType $provider = VectorStoreProviderType::PINECONE;

    public function __construct()
    {
        tap(VectorStoreProviderType::tryFrom(Config::get('vector-store.default')), function (?VectorStoreProviderType $provider) {
            if ($provider === null) {
                throw new InvalidProviderException();
            }

            $this->provider = $provider;
        });
    }

    /**
     * Vector database provider
     *
     *
     * @return $this
     */
    public function provider(VectorStoreProviderType $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    /**
     * Returns an instance of your active provider
     */
    public function instance(): DatabaseAdapterAbstract
    {
        return match ($this->provider) {
            VectorStoreProviderType::PINECONE => new Pinecone(),
            VectorStoreProviderType::WEAVIATE => new Weaviate(),
        };
    }

    /**
     * @deprecated Pinecone no longer requires a dataset to operate
     */
    public function dataset(): DatabaseAdapterAbstract
    {
        return $this->instance();
    }
}
