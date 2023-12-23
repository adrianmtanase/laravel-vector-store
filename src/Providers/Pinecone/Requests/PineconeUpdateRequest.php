<?php

namespace AdrianTanase\VectorStore\Providers\Pinecone\Requests;

use AdrianTanase\VectorStore\Providers\Pinecone\Abstracts\PineconeRequestAbstract;

class PineconeUpdateRequest extends PineconeRequestAbstract {
	public function __construct(
		protected string  $id,
		protected array   $values = [],
		protected array   $sparseValues = [],
		protected array   $setMetadata = []
	)
	{
	}

	public function id(string $id): self {
		$this->id = $id;

		return $this;
	}

	public function values(array $values) : self {
		$this->values = $values;

		return $this;
	}

	public function sparseValues(array $sparseValues) : self {
		$this->sparseValues = $sparseValues;

		return $this;
	}

	public function setMetadata(array $setMetadata): self {
		$this->setMetadata = $setMetadata;

		return $this;
	}
}