<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate\Requests;

use AdrianTanase\VectorStore\Providers\Weaviate\Abstracts\WeaviateRequestAbstract;

class WeaviateDeleteRequest extends WeaviateRequestAbstract
{
    public function __construct(
        protected ?string $id = null
    ) {
    }

    public static function build(): self
    {
        return new self();
    }

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
