<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate\Concerns;

use AdrianTanase\VectorStore\Providers\Weaviate\Serializable\QueryParameters\WeaviateQueryParameters;

trait WeaviateGraphQL
{
    abstract public function withParameters(WeaviateQueryParameters $parameters): self;
}
