<?php

namespace AdrianTanase\VectorStore\Providers\Pinecone\Abstracts;

use AdrianTanase\VectorStore\Abstracts\DatabaseAdapterRequestAbstract;
use AdrianTanase\VectorStore\Contracts\DatabaseAdapterRequestContract;

abstract class PineconeRequestAbstract extends DatabaseAdapterRequestAbstract implements DatabaseAdapterRequestContract
{
    abstract public static function build(): self;
}
