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
use Probots\Pinecone\Requests\Exceptions\MissingNameException;
use Throwable;

/**
 * @class Pinecone
 *
 * Vector store adapter for Pinecone.io
 */
class Pinecone extends DatabaseAdapterAbstract
{
    private PineconeClient $client;

    public function __construct(string $dataset)
    {
        parent::__construct($dataset);

        $this->client = new PineconeClient(Config::get('vector-store.pinecone_api_key'), Config::get('vector-store.pinecone_environment'));
    }

    /**
     * @param  PineconeGetRequest  $request
     *
     * @throws MissingNameException&Throwable
     */
    public function get(DatabaseAdapterRequestContract $request): array
    {
        assert($request instanceof PineconeGetRequest, new InvalidDatabaseAdapterRequestException());

        return $this->client->index($this->dataset)
            ->vectors()
            ->fetch(
                ...array_merge(
                    $request->serialize(),
                    [
                        'namespace' => $this->getNamespace(),
                    ]
                )
            )->json();
    }

    /**
     * @param  PineconeDeleteRequest  $request
     *
     * @throws MissingNameException&Throwable
     */
    public function delete(DatabaseAdapterRequestContract $request): array
    {
        assert($request instanceof PineconeDeleteRequest, new InvalidDatabaseAdapterRequestException());

        return $this->client->index($this->dataset)
            ->vectors()
            ->delete(
                ...array_merge(
                    $request->serialize(),
                    [
                        'namespace' => $this->getNamespace(),
                    ]
                )
            )->json();
    }

    /**
     * @param  PineconeUpsertRequest[]|PineconeUpsertRequest  $request
     *
     * @throws MissingNameException&Throwable
     */
    public function create(array|DatabaseAdapterRequestContract $request): array
    {
        return $this->upsert($request);
    }

    /**
     * @param  PineconeUpsertRequest[]|PineconeUpsertRequest  $request
     *
     * @throws MissingNameException&Throwable
     */
    public function upsert(array|DatabaseAdapterRequestContract $request): array
    {

        if (is_iterable($request)) {
            collect($request)->each(function ($item) {
                assert($item instanceof PineconeUpsertRequest, new InvalidDatabaseAdapterRequestException());
            });
        } else {
            assert($request instanceof PineconeUpsertRequest, new InvalidDatabaseAdapterRequestException());
        }


      $response = $this->client->data()
            ->vectors()
            ->upsert(
                vectors: $request instanceof DatabaseAdapterRequestContract ?
                    $request->serialize() :
                    collect($request)->map(function ($request) {
                        return $request->serialize();
                    })->toArray(),
                namespace: $this->getNamespace()
            );

      return [$response->body()];
    }

    /**
     * @param  PineconeUpdateRequest  $request
     *
     * @throws MissingNameException&Throwable
     */
    public function update(DatabaseAdapterRequestContract $request): array
    {
        assert($request instanceof PineconeUpdateRequest, new InvalidDatabaseAdapterRequestException());

        return $this->client->index($this->dataset)
            ->vectors()
            ->update(
                ...array_merge(
                    $request->serialize(),
                    [
                        'namespace' => $this->getNamespace(),
                    ]
                )
            )->json();
    }

    /**
     * @param  PineconeQueryRequest  $request
     *
     * @throws MissingNameException&Throwable
     */
    public function query(DatabaseAdapterRequestContract $request): array
    {
        assert($request instanceof PineconeQueryRequest, new InvalidDatabaseAdapterRequestException());

        return $this->client->data()
            ->vectors()
            ->query(
                vector: $request->serialize()['vector'],
                topK: $request->serialize()['topK'],
                namespace: $this->getNamespace()
            )
            ->json();
    }
}
