<?php

namespace AdrianTanase\VectorStore\Abstracts;

use AdrianTanase\VectorStore\Contracts\DatabaseAdapterRequestContract;

abstract class DatabaseAdapterRequestAbstract implements DatabaseAdapterRequestContract {
	public function serialize(): array {
		// Serialize the properties of this class
		$properties = get_object_vars($this);

		$data = [];
		foreach ($properties as $name => $value) {
			if (is_array($value) && empty($value)) {
				continue;
			}

			$data[$name] = $value;
		}

		return $data;
	}
}