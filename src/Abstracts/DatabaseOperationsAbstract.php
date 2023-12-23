<?php

namespace AdrianTanase\VectorStore\Abstracts;

use AdrianTanase\VectorStore\Contracts\DatabaseOperationsContract;

abstract class DatabaseOperationsAbstract implements DatabaseOperationsContract
{
	private string $namespace;

	function namespace(string $namespace): DatabaseOperationsContract
	{
		$this->namespace = $namespace;

		return $this;
	}

	protected function getNamespace(): string {
		return $this->namespace;
	}
}