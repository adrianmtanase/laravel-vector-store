<?php

namespace AdrianTanase\VectorStore\Contracts;

interface DatabaseOperationsContract {
	function delete(DatabaseAdapterRequestContract $request): mixed;
	function create(DatabaseAdapterRequestContract $request): mixed;
	function update(DatabaseAdapterRequestContract $request): mixed;
	function query(DatabaseAdapterRequestContract $request): mixed;
	function namespace(string $namespace): self;
}