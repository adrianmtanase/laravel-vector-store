<?php

namespace AdrianTanase\VectorStore\Contracts;

interface DatabaseAdapterRequestContract {
	public function serialize(): mixed;
}