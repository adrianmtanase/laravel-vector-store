<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate\Requests;

use AdrianTanase\VectorStore\Concerns\IgnoresNullProperties;
use AdrianTanase\VectorStore\Providers\Weaviate\Abstracts\WeaviateParametersAbstract;
use AdrianTanase\VectorStore\Providers\Weaviate\Abstracts\WeaviateRequestAbstract;
use AdrianTanase\VectorStore\Providers\Weaviate\Concerns\WeaviateGraphQL;
use AdrianTanase\VectorStore\Providers\Weaviate\Serializable\QueryParameters\WeaviateQueryParameters;
use Illuminate\Support\Str;

class WeaviateQueryRequest extends WeaviateRequestAbstract {
	use WeaviateGraphQL;
	use IgnoresNullProperties;

	public function __construct(
		protected array   $vector = [],
		protected ?float   $distance = null,
		protected ?float   $certainty = null,
		protected array|string $properties = [],
		protected ?WeaviateParametersAbstract $withParameters = null,
	)
	{
	}

	public static function build(): self
	{
		return new self();
	}

	public function vector(array $vector): self {
		$this->vector = $vector;

		return $this;
	}

	public function distance(float $distance): self {
		$this->distance = $distance;

		return $this;
	}

	public function certainty(float $certainty): self {
		$this->certainty = $certainty;

		return $this;
	}

	public function properties(array|string $properties): self {
		$this->properties = $properties;

		return $this;
	}

	public function withParameters(WeaviateQueryParameters $parameters): self {
		$this->withParameters = $parameters;

		return $this;
	}

	public function getGraphQLParameters(): string {
		$serializedRequest = $this->serialize();
		$collection = collect($serializedRequest);

		$nearVectorParameters = sprintf('vector: %s', json_encode($collection->get('vector'), JSON_THROW_ON_ERROR));

		if ($collection->has('distance')) {
			$nearVectorParameters .= sprintf(', distance: %s', $collection->get('distance'));
		}

		if ($collection->has('certainty')) {
			$nearVectorParameters .= sprintf(', certainty: %s', $collection->get('certainty'));
		}

		if ($collection->has('withParameters')) {
			$strings = collect($collection->get('withParameters'))->map(function ($value, $key) {
				return Str::of($key)->append(': {', $value, '}');
			});

			$otherParameters = $strings->join(', ');
		}

		return sprintf('nearVector: {%s}%s', $nearVectorParameters, isset($otherParameters) ? (', '.$otherParameters) : '');
	}

	public function getGraphQLProperties(): string {
		return is_array($this->properties) ? implode(', ', $this->properties) : $this->properties;
	}
}