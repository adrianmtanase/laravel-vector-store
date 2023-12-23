<?php

namespace AdrianTanase\VectorStore\Contracts;

use AdrianTanase\VectorStore\Abstracts\DatabaseAdapterAbstract;
use AdrianTanase\VectorStore\Enums\VectorStoreProviderType;

interface VectorStoreContract {
	public function provider(VectorStoreProviderType $provider): self;
	public function dataset(string $dataset): DatabaseAdapterAbstract;
}