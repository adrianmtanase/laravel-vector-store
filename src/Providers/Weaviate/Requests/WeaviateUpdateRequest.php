<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate\Requests;

use AdrianTanase\VectorStore\Providers\Weaviate\Abstracts\WeaviateRequestAbstract;

class WeaviateUpdateRequest extends WeaviateRequestAbstract
{
    public function __construct(
        protected ?string $id = null,
        protected array $data = [],
        protected bool $replace = false,
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

    public function data(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function replace(bool $replace): self
    {
        $this->replace = $replace;

        return $this;
    }
}
