<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate\Requests;

use AdrianTanase\VectorStore\Providers\Weaviate\Abstracts\WeaviateRequestAbstract;

class WeaviateGetRequest extends WeaviateRequestAbstract {
	public function __construct(protected ?array $ids = null)
	{
	}

	public function ids(array $ids): self {
		$this->ids = $ids;

		return $this;
	}

	public static function build(): self
	{
		return new self();
	}
}