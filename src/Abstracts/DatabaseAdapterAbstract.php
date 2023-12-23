<?php

namespace AdrianTanase\VectorStore\Abstracts;

use AdrianTanase\VectorStore\Exceptions\InvalidDatabaseAdapterRequestException;

abstract class DatabaseAdapterAbstract extends DatabaseOperationsAbstract
{
	protected string $dataset;

	public function __construct(string $dataset)
	{
		$this->dataset = $dataset;
	}

	protected function assertInstance($className): void {
		assert($this instanceof $className, new InvalidDatabaseAdapterRequestException());
	}
}