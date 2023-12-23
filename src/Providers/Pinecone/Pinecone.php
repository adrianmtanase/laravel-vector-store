<?php

namespace AdrianTanase\VectorStore\Providers\Pinecone;

use AdrianTanase\VectorStore\Abstracts\DatabaseAdapterAbstract;
use AdrianTanase\VectorStore\Contracts\DatabaseAdapterRequestContract;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeDeleteRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeGetRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeQueryRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeUpdateRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeUpsertRequest;
use Illuminate\Support\Facades\Config;
use Probots\Pinecone\Client as PineconeClient;
use Probots\Pinecone\Requests\Exceptions\MissingNameException;
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
		$this->assertInstance(PineconeGetRequest::class);

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

	/**
	 * @throws MissingNameException
	 */
	function delete(DatabaseAdapterRequestContract $request): mixed
	{
		$this->assertInstance(PineconeDeleteRequest::class);

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

	/**
	 * @throws MissingNameException
	 */
	function upsert(DatabaseAdapterRequestContract $request): Response
	{
		$this->assertInstance(PineconeUpsertRequest::class);

		return $this->client->index($this->dataset)
			->vectors()
			->upsert(
				vectors: $request->serialize(),
				namespace: $this->getNamespace()
			);
	}

	/**
	 * @throws MissingNameException
	 */
	function update(DatabaseAdapterRequestContract $request): mixed
	{
		$this->assertInstance(PineconeUpdateRequest::class);

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

	/**
	 * @throws MissingNameException
	 */
	function query(DatabaseAdapterRequestContract $request): Response
	{
		$this->assertInstance(PineconeQueryRequest::class);

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