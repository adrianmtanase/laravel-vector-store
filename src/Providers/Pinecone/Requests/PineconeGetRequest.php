<?php

namespace AdrianTanase\VectorStore\Providers\Pinecone\Requests;

use AdrianTanase\VectorStore\Providers\Pinecone\Abstracts\PineconeRequestAbstract;

class PineconeGetRequest extends PineconeRequestAbstract
{
    public function __construct(protected ?array $ids = null) {}

    public function ids(array $ids): self
    {
        $this->ids = $ids;

        return $this;
    }

    public static function build(): self
    {
        return new self();
    }
}
