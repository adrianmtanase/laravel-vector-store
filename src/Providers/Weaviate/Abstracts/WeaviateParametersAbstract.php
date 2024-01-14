<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate\Abstracts;

use AdrianTanase\VectorStore\Abstracts\DatabaseAdapterRequestAbstract;
use AdrianTanase\VectorStore\Contracts\DatabaseAdapterRequestContract;

abstract class WeaviateParametersAbstract extends DatabaseAdapterRequestAbstract implements DatabaseAdapterRequestContract {
	abstract public static function build(): self;
}