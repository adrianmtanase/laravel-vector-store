<?php

namespace AdrianTanase\VectorStore\Providers\Weaviate;

use AdrianTanase\VectorStore\Abstracts\DatabaseAdapterAbstract;
use AdrianTanase\VectorStore\Contracts\DatabaseAdapterRequestContract;
use AdrianTanase\VectorStore\Exceptions\InvalidDatabaseAdapterRequestException;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateCreateRequest;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateDeleteRequest;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateQueryRequest;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateUpdateRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use JsonException;
use Weaviate\Collections\ObjectCollection;
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

	function client() {
		return $this->client;
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
	 * @return ObjectCollection
	 */
	function batchCreate(array $requests): ObjectCollection
	{
		$objects = collect($requests)->map(function ($request) {
			assert($request instanceof WeaviateCreateRequest, new InvalidDatabaseAdapterRequestException);

			$serialized = $request->serialize();
			$serialized['class'] = $this->getNamespace();

			return $serialized;
		})->toArray();

		return $this->client->batch()->create(objects: $objects);
	}

	function delete(DatabaseAdapterRequestContract $request): mixed
	{
		assert($request instanceof WeaviateDeleteRequest, new InvalidDatabaseAdapterRequestException);

		return $this->client->dataObject()->delete(className: $this->getNamespace(), id: $request->serialize()['id']);
	}

	function update(DatabaseAdapterRequestContract $request): mixed
	{
		assert($request instanceof WeaviateUpdateRequest, new InvalidDatabaseAdapterRequestException);

		return $this->client->dataObject()->update(
			...array_merge(
				$request->serialize(),
				[
					'className' => $this->getNamespace()
				]
			),
		);
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