<?php

namespace AdrianTanase\VectorStore\Test;

use AdrianTanase\VectorStore\Facades\VectorStore;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeDeleteRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeGetRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeQueryRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeUpdateRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeUpsertRequest;

class PineconeTest extends TestCase
{
    private array $vectorIsLitEmbedding;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vectorIsLitEmbedding = json_decode(file_get_contents('tests/ada-002-vector-store-is-lit-embedding.json'), true);
    }

    public function test_it_can_upsert_to_pinecone()
    {
        $response = VectorStore::instance()
            ->namespace('general')
            ->create(
                PineconeUpsertRequest::build()
                    ->id('1')
                    ->values($this->vectorIsLitEmbedding)
                    ->metadata([
                        'text' => 'Vector store is lit!',
                    ])
            );

        $this->assertEquals(1, $response['upsertedCount'], 'Pinecone: Upsert does not equal 1!');
    }

    public function test_it_can_upsert_multiple_to_pinecone()
    {
        $requests = collect();

        $requests->add(
            PineconeUpsertRequest::build()
                ->id('1')
                ->values($this->vectorIsLitEmbedding)
                ->metadata([
                    'text' => 'Vector store is lit!',
                ])
        );

        $requests->add(
            PineconeUpsertRequest::build()
                ->id('2')
                ->values($this->vectorIsLitEmbedding)
                ->metadata([
                    'text' => 'Vector store is lit!',
                ])
        );

        $response = VectorStore::instance()
            ->namespace('general')
            ->create($requests->toArray());

        $this->assertEquals(2, $response['upsertedCount'], 'Pinecone: Upsert does not equal 2!');
    }

    /**
     * @depends test_it_can_upsert_to_pinecone
     *
     * @return void
     */
    public function test_it_can_get_vectors_by_id_pinecone()
    {
        $response = VectorStore::instance()
            ->namespace('general')
            ->get(
                PineconeGetRequest::build()
                    ->ids(['1'])
            );

        $this->assertEquals('Vector store is lit!', $response['vectors']['1']['metadata']['text'], 'Pinecone: Vector id 1 incorrect');
    }

    /**
     * @depends test_it_can_upsert_to_pinecone
     *
     * @return void
     */
    public function test_it_can_query_vectors_pinecone()
    {
        $response = VectorStore::instance()
            ->namespace('general')
            ->query(
                PineconeQueryRequest::build()
                    ->vector($this->vectorIsLitEmbedding)
            );

        $this->assertEquals('Vector store is lit!', $response['matches'][0]['metadata']['text'], 'Pinecone: Vector id 1 incorrect');
    }

    /**
     * @depends test_it_can_upsert_to_pinecone
     *
     * @return void
     */
    public function test_it_can_update_vectors_pinecone()
    {
        // update the metadata
        VectorStore::instance()
            ->namespace('general')
            ->update(
                PineconeUpdateRequest::build()
                    ->id('1')
                    ->setMetadata([
                        'text' => 'Vector store is lit!',
                        'new_text' => 'New metadata',
                    ])
            );

        // check if the new metadata contains the new field
        $response = VectorStore::instance()
            ->namespace('general')
            ->get(
                PineconeGetRequest::build()
                    ->ids(['1'])
            );

        $this->assertEquals('New metadata', $response['vectors']['1']['metadata']['new_text'], 'Pinecone: New metadata incorrect');
    }

    /**
     * @depends test_it_can_upsert_to_pinecone
     * @depends test_it_can_update_vectors_pinecone
     * @depends test_it_can_query_vectors_pinecone
     * @depends test_it_can_get_vectors_by_id_pinecone
     *
     * @return void
     */
    public function test_it_can_delete_vectors_pinecone()
    {
        // update the metadata
        VectorStore::instance()
            ->namespace('general')
            ->delete(
                PineconeDeleteRequest::build()
                    ->ids(['1'])
            );

        // check if the new metadata contains the new field
        $response = VectorStore::instance()
            ->namespace('general')
            ->get(
                PineconeGetRequest::build()
                    ->ids(['1'])
            );

        $this->assertEmpty($response['vectors'], 'Pinecone: Vector not deleted!');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
