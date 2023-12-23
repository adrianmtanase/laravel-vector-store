<?php

namespace AdrianTanase\VectorStore;

use AdrianTanase\VectorStore\Abstracts\DatabaseAdapterAbstract;
use AdrianTanase\VectorStore\Contracts\VectorStoreContract;
use AdrianTanase\VectorStore\Enums\VectorStoreProviderType;
use AdrianTanase\VectorStore\Exceptions\InvalidProviderException;
use AdrianTanase\VectorStore\Providers\Pinecone\Pinecone;
use Illuminate\Support\Facades\Config;

class DatabaseAdapter implements VectorStoreContract {
	protected VectorStoreProviderType $provider = VectorStoreProviderType::PINECONE;

	public function __construct()
	{
		tap(VectorStoreProviderType::tryFrom(Config::get('vector-store.default')), function (?VectorStoreProviderType $provider) {
			if (null === $provider) {
				throw new InvalidProviderException();
			}

			$this->provider = $provider;
		});
	}

	public function provider(VectorStoreProviderType $provider): self {
		$this->provider = $provider;

		return $this;
	}

	public function dataset(string $dataset): DatabaseAdapterAbstract {
		return match ($this->provider) {
			VectorStoreProviderType::PINECONE => new Pinecone($dataset)
		};
	}
}