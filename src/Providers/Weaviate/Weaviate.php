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
use Illuminate\Support\Facades\Config;
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

    public function __construct()
    {
        $this->client = new WeaviateClient(Config::get('vector-store.weaviate_url'), Config::get('vector-store.weaviate_api_key'));
    }

    /**
     * Returns an instance of the Weaviate client
     *
     * @return WeaviateClient
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * @param  WeaviateCreateRequest[]|WeaviateCreateRequest  $request
     */
    public function create(array|DatabaseAdapterRequestContract $request): ObjectModel|ObjectCollection
    {
        if (is_iterable($request)) {
            return $this->batchCreate($request);
        }

        assert($request instanceof WeaviateCreateRequest, new InvalidDatabaseAdapterRequestException);

        return $this->client->dataObject()->create(data: array_merge(
            $request->serialize(),
            [
                'class' => $this->getNamespace(),
            ]
        )
        );
    }

    /**
     * @param  WeaviateCreateRequest[]  $requests
     */
    public function batchCreate(array $requests): ObjectCollection
    {
        $objects = collect($requests)->map(function ($request) {
            assert($request instanceof WeaviateCreateRequest, new InvalidDatabaseAdapterRequestException);

            $serialized = $request->serialize();
            $serialized['class'] = $this->getNamespace();

            return $serialized;
        })->toArray();

        return $this->client->batch()->create(objects: $objects);
    }

    /**
     * @param  WeaviateDeleteRequest  $request
     */
    public function delete(DatabaseAdapterRequestContract $request): Response
    {
        assert($request instanceof WeaviateDeleteRequest, new InvalidDatabaseAdapterRequestException);

        return $this->client->dataObject()->delete(className: $this->getNamespace(), id: $request->serialize()['id']);
    }

    /**
     * @param  WeaviateUpdateRequest  $request
     */
    public function update(DatabaseAdapterRequestContract $request): bool
    {
        assert($request instanceof WeaviateUpdateRequest, new InvalidDatabaseAdapterRequestException);

        return $this->client->dataObject()->update(
            ...array_merge(
                $request->serialize(),
                [
                    'className' => $this->getNamespace(),
                ]
            ),
        );
    }

    /**
     * Query by vector
     *
     * @param  WeaviateQueryRequest  $request
     *
     * @throws JsonException
     */
    public function query(DatabaseAdapterRequestContract $request): array
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
     * @param  WeaviateQueryRequest  $request
     *
     * @throws JsonException
     */
    public function get(DatabaseAdapterRequestContract $request): array
    {
        return $this->query($request);
    }

    /**
     * Raw query using GraphQL
     */
    public function rawQuery(string $graphQLQuery): array
    {
        return $this->client->graphql()->get($graphQLQuery);
    }
}
