<?php

namespace AdrianTanase\VectorStore\Providers\Pinecone\Requests;

use AdrianTanase\VectorStore\Providers\Pinecone\Abstracts\PineconeRequestAbstract;

class PineconeUpsertRequest extends PineconeRequestAbstract {
	public function __construct(protected ?string $id = null, protected ?array $values = [], protected ?array $sparseValues = [], protected ?array $metadata = null)
	{
	}

	public static function build(): self {
		return new self();
	}

	public function id(string|int|null $id): self {
		$this->id = is_numeric($id) ? (string) $id : $id;

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

	public function metadata(array $metadata): self {
		$this->metadata = $metadata;

		return $this;
	}
}