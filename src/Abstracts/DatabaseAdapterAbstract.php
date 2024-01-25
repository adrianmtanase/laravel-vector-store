<?php

namespace AdrianTanase\VectorStore\Abstracts;

abstract class DatabaseAdapterAbstract extends DatabaseOperationsAbstract
{
    protected string $dataset;

    public function __construct(string $dataset)
    {
        $this->dataset = $dataset;
    }
}
