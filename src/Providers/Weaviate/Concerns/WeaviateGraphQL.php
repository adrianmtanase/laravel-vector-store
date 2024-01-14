<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate\Concerns;

use AdrianTanase\VectorStore\Providers\Weaviate\Serializable\QueryParameters\WeaviateQueryParameters;

trait WeaviateGraphQL {
	abstract function withParameters(WeaviateQueryParameters $parameters): self;
}