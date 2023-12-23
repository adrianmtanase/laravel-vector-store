<?php

namespace AdrianTanase\VectorStore\Contracts;

interface DatabaseOperationsContract {
	function get(DatabaseAdapterRequestContract $request): mixed;
	function delete(DatabaseAdapterRequestContract $request): mixed;
	function upsert(DatabaseAdapterRequestContract $request): mixed;
	function update(DatabaseAdapterRequestContract $request): mixed;
	function query(DatabaseAdapterRequestContract $request): mixed;
	function namespace(string $namespace): self;
}