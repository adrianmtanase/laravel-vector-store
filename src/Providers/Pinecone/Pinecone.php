<?php

namespace AdrianTanase\VectorStore\Providers\Pinecone;

use AdrianTanase\VectorStore\Abstracts\DatabaseAdapterAbstract;
use AdrianTanase\VectorStore\Contracts\DatabaseAdapterRequestContract;
use AdrianTanase\VectorStore\Exceptions\InvalidDatabaseAdapterRequestException;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeDeleteRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeGetRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeQueryRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeUpdateRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeUpsertRequest;
use Illuminate\Support\Facades\Config;
use Probots\Pinecone\Client as PineconeClient;
use Saloon\Contracts\Response;

/**
 * @class Pinecone
 *
 * Vector store adapter for Pinecone.io
 */
class Pinecone extends DatabaseAdapterAbstract {

	private PineconeClient $client;

	public function __construct(string $dataset)
	{
		parent::__construct($dataset);

		$this->client = new PineconeClient(Config::get('vector-store.pinecone_api_key'), Config::get('vector-store.pinecone_environment'));
	}

	function get(DatabaseAdapterRequestContract $request): mixed
	{
		assert($request instanceof PineconeGetRequest, new InvalidDatabaseAdapterRequestException());

		return $this->client->index($this->dataset)
			->vectors()
			->fetch(
				...array_merge(
					$request->serialize(),
					[
						'namespace' => $this->getNamespace()
					]
				)
			);
	}

	function delete(DatabaseAdapterRequestContract $request): mixed
	{
		assert($request instanceof PineconeDeleteRequest, new InvalidDatabaseAdapterRequestException());

		return $this->client->index($this->dataset)
			->vectors()
			->delete(
				...array_merge(
					$request->serialize(),
					[
						'namespace' => $this->getNamespace()
					]
				)
			);
	}

	function upsert(DatabaseAdapterRequestContract $request): Response
	{
		assert($request instanceof PineconeUpsertRequest, new InvalidDatabaseAdapterRequestException());

		return $this->client->index($this->dataset)
			->vectors()
			->upsert(
				vectors: $request->serialize(),
				namespace: $this->getNamespace()
			);
	}

	function update(DatabaseAdapterRequestContract $request): mixed
	{
		assert($request instanceof PineconeUpdateRequest, new InvalidDatabaseAdapterRequestException());

		return $this->client->index($this->dataset)
			->vectors()
			->update(
				...array_merge(
					$request->serialize(),
					[
						'namespace' => $this->getNamespace()
					]
				)
			);
	}

	function query(DatabaseAdapterRequestContract $request): Response
	{
		assert($request instanceof PineconeQueryRequest, new InvalidDatabaseAdapterRequestException());

		return $this->client->index($this->dataset)
			->vectors()
			->query(
				...array_merge(
					$request->serialize(),
					[
						'namespace' => $this->getNamespace()
					]
				)
			);
	}
}