<?php

namespace AdrianTanase\VectorStore\Contracts;

interface DatabaseOperationsContract
{
    public function delete(DatabaseAdapterRequestContract $request): mixed;

    /**
     * @param  DatabaseAdapterRequestContract[]|DatabaseAdapterRequestContract  $request
     */
    public function create(array|DatabaseAdapterRequestContract $request): mixed;

    public function update(DatabaseAdapterRequestContract $request): mixed;

    public function query(DatabaseAdapterRequestContract $request): mixed;

    public function namespace(string $namespace): self;
}
