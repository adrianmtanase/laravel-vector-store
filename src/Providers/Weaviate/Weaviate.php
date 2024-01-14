<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate;

use AdrianTanase\VectorStore\Abstracts\DatabaseAdapterAbstract;
use AdrianTanase\VectorStore\Contracts\DatabaseAdapterRequestContract;
use AdrianTanase\VectorStore\Exceptions\InvalidDatabaseAdapterRequestException;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateCreateRequest;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateQueryRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use JsonException;
use Weaviate\Model\ObjectModel;
use Weaviate\Weaviate as WeaviateClient;

/**
 * @class Weaviate
 *
 * Vector store adapter for Pinecone.io
 */
class Weaviate extends DatabaseAdapterAbstract
{

	private WeaviateClient $client;

	public function __construct(string $dataset)
	{
		parent::__construct($dataset);

		$this->client = new WeaviateClient(Config::get('vector-store.weaviate_url'), Config::get('vector-store.weaviate_api_key'));
	}

	function create(DatabaseAdapterRequestContract $request): ObjectModel
	{
		assert($request instanceof WeaviateCreateRequest, new InvalidDatabaseAdapterRequestException);

		return $this->client->dataObject()->create(data: array_merge(
				$request->serialize(),
				[
					'class' => $this->getNamespace()
				]
			)
		);
	}

	/**
	 * @param DatabaseAdapterRequestContract[] $requests
	 *
	 * @return ObjectModel
	 */
	function batchCreate(array $requests): ObjectModel
	{
		$objects = collect($requests)->each(function ($request) {
			assert($request instanceof WeaviateCreateRequest, new InvalidDatabaseAdapterRequestException);

			$serialized = $request->serialize();
			$serialized['class'] = $this->getNamespace();

			return $serialized;
		})->toArray();

		return $this->client->batch()->create(objects: $objects);
	}

	function get(DatabaseAdapterRequestContract $request): mixed
	{
		// TODO: Implement get() method.
	}

	function delete(DatabaseAdapterRequestContract $request): mixed
	{
		// TODO: Implement delete() method.
	}

	function update(DatabaseAdapterRequestContract $request): mixed
	{
		// TODO: Implement update() method.
	}

	/**
	 * Query by vector
	 *
	 * @throws JsonException
	 */
	function query(DatabaseAdapterRequestContract $request): array
	{
		assert($request instanceof WeaviateQueryRequest, new InvalidDatabaseAdapterRequestException());

		return $this->client->graphql()->get(sprintf('{
		    Get {
		      %s(%s) {
		        %s
		      }
		    }
		}', ucfirst(strtolower($this->getNamespace())), $request->getGraphQLParameters(), $request->getGraphQLProperties()));
	}

	/**
	 * Raw query using GraphQL
	 */
	function rawQuery(string $graphQLQuery): array
	{
		return $this->client->graphql()->get($graphQLQuery);
	}
}