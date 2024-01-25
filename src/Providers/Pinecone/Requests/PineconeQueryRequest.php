<?php

namespace AdrianTanase\VectorStore\Providers\Pinecone\Requests;

use AdrianTanase\VectorStore\Providers\Pinecone\Abstracts\PineconeRequestAbstract;

class PineconeQueryRequest extends PineconeRequestAbstract
{
    public function __construct(
        protected array $vector = [],
        protected int $topK = 3,
        protected array $filter = [],
        protected bool $includeMetadata = true,
        protected bool $includeVector = false,
        protected ?string $id = null
    ) {
    }

    public static function build(): self
    {
        return new self();
    }

    public function vector(array $vector): self
    {
        $this->vector = $vector;

        return $this;
    }

    public function topK(int $topK): self
    {
        $this->topK = $topK;

        return $this;
    }

    public function filter(array $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    public function includeMetadata(bool $includeMetadata): self
    {
        $this->includeMetadata = $includeMetadata;

        return $this;
    }

    public function includeVector(bool $includeVector): self
    {
        $this->includeVector = $includeVector;

        return $this;
    }

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
