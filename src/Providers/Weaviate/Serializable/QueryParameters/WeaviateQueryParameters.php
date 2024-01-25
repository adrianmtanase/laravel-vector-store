<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate\Serializable\QueryParameters;

use AdrianTanase\VectorStore\Providers\Weaviate\Abstracts\WeaviateParametersAbstract;

class WeaviateQueryParameters extends WeaviateParametersAbstract
{
    public function __construct(
        protected ?string $bm25 = null,
        protected ?string $where = null,
        protected ?string $hybrid = null,
        protected ?string $limit = null,
        protected ?string $autocut = null,
        protected ?string $group = null,
        protected ?string $sort = null,
        protected ?string $groupBy = null,
        protected ?string $after = null,
        protected ?string $offset = null,
    ) {
    }

    public static function build(): self
    {
        return new self();
    }

    public function bm25(string $bm25): self
    {
        $this->bm25 = $bm25;

        return $this;
    }

    public function where(string $where): self
    {
        $this->where = $where;

        return $this;
    }

    public function hybrid(string $hybrid): self
    {
        $this->hybrid = $hybrid;

        return $this;
    }

    public function limit(string $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function autocut(string $autocut): self
    {
        $this->autocut = $autocut;

        return $this;
    }

    public function group(string $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function sort(string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function groupBy(string $groupBy): self
    {
        $this->groupBy = $groupBy;

        return $this;
    }

    public function after(string $after): self
    {
        $this->after = $after;

        return $this;
    }

    public function offset(string $offset): self
    {
        $this->offset = $offset;

        return $this;
    }
}
