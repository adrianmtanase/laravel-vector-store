<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate\Requests;

use AdrianTanase\VectorStore\Providers\Weaviate\Abstracts\WeaviateRequestAbstract;

class WeaviateCreateRequest extends WeaviateRequestAbstract {
	public function __construct(protected array $properties = [], protected array $vector = [])
	{
	}

	public static function build(): self {
		return new self();
	}

	public function properties(array $properties) : self {
		$this->properties = $properties;

		return $this;
	}

	public function vector(array $vector) : self {
		$this->vector = $vector;

		return $this;
	}
}