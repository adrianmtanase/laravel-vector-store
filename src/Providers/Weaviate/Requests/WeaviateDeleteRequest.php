<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate\Requests;

use AdrianTanase\VectorStore\Providers\Weaviate\Abstracts\WeaviateRequestAbstract;

class WeaviateDeleteRequest extends WeaviateRequestAbstract {
	public function __construct(
		protected ?array $ids = null,
		protected bool $deleteAll = false,
		protected ?array $filter = []
	)
	{
	}

	public static function build(): self
	{
		return new self();
	}

	public function ids(array $ids): self {
		$this->ids = $ids;

		return $this;
	}

	public function deleteAll(bool $deleteAll) : self {
		$this->deleteAll = $deleteAll;

		return $this;
	}

	public function filter(array $filter): self {
		$this->filter = $filter;

		return $this;
	}
}