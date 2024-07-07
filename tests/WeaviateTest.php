<?php

namespace AdrianTanase\VectorStore\Test;

use AdrianTanase\VectorStore\Enums\VectorStoreProviderType;
use AdrianTanase\VectorStore\Facades\VectorStore;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateCreateRequest;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateDeleteRequest;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateQueryRequest;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateUpdateRequest;
use AdrianTanase\VectorStore\Providers\Weaviate\Serializable\QueryParameters\WeaviateQueryParameters;
use Weaviate\Collections\ObjectCollection;
use Weaviate\Model\ObjectModel;

class WeaviateTest extends TestCase
{
    private array $vectorIsLitEmbedding;

    private array $vectorStoreUpdateTestEmbedding;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vectorIsLitEmbedding = json_decode(file_get_contents('tests/ada-002-vector-store-is-lit-embedding.json'), true);
        $this->vectorStoreUpdateTestEmbedding = json_decode(file_get_contents('tests/ada-002-vector-store-update-test-embedding.json'), true);
    }

    /**
     * @depends test_it_can_query_vector
     *
     * @return void
     */
    public function test_it_can_batch_create_vector()
    {
        /**
         * @var ObjectCollection $response
         */
        $response = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
            ->instance()
            ->namespace('general')
            ->batchCreate(
                [
                    WeaviateCreateRequest::build()
                        ->vector($this->vectorIsLitEmbedding)
                        ->properties([
                            'text' => 'Vector store has been batch created 1!',
                        ]),
                    WeaviateCreateRequest::build()
                        ->vector($this->vectorIsLitEmbedding)
                        ->properties([
                            'text' => 'Vector store has been batch created 1!',
                        ]),
                ]
            );

        $this->assertEquals($response->first()['properties']['text'], 'Vector store has been batch created 1!', 'Weaviate: Failed to create batch records');
    }

    public function test_it_can_create_vector()
    {
        /**
         * @var ObjectModel $response
         */
        $response = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
            ->instance()
            ->namespace('general')
            ->create(
                WeaviateCreateRequest::build()
                    ->vector($this->vectorIsLitEmbedding)
                    ->properties([
                        'text' => 'Vector store is lit!',
                    ])
            );

        $this->assertEquals($response->getProperties()->get('text'), 'Vector store is lit!', 'Weaviate: Failed to create a record');
    }

    /**
     * @depends test_it_can_create_vector
     *
     * @return void
     */
    public function test_it_can_query_vector()
    {
        $response = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
            ->instance()
            ->namespace('general')
            ->query(
                WeaviateQueryRequest::build()
                    ->vector($this->vectorIsLitEmbedding)
                    ->properties(['text'])
                    ->withId()
                    ->withParameters(WeaviateQueryParameters::build()->group('type: closest, force: 1'))
            );

        $this->assertEquals('Vector store is lit!', $response['data']['Get']['General'][0]['text'], 'Weaviate: Vector query wrong!');
    }

    /**
     * @depends test_it_can_create_vector
     *
     * @return void
     */
    public function test_it_can_update_vector()
    {
        $randomNamespace = ucfirst(fake()->word());

        /**
         * @var ObjectModel $response
         */
        $create = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
            ->instance()
            ->namespace($randomNamespace)
            ->create(
                WeaviateCreateRequest::build()
                    ->vector($this->vectorStoreUpdateTestEmbedding)
                    ->properties([
                        'text' => 'First iteration!',
                    ])
            );

        $this->assertEquals($create->getProperties()->get('text'), 'First iteration!', 'Weaviate: Failed to create a record');

        // update the vector
        $update = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
            ->instance()
            ->namespace($randomNamespace)
            ->update(
                WeaviateUpdateRequest::build()
                    ->id($create->getId())
                    ->data(WeaviateCreateRequest::build()->properties(['text' => 'Second iteration!'])->serialize())
            );

        $this->assertTrue($update, 'Weaviate update failed!');

        // check if the new metadata contains the new field
        $response = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
            ->instance()
            ->namespace($randomNamespace)
            ->query(
                WeaviateQueryRequest::build()
                    ->vector($this->vectorStoreUpdateTestEmbedding)
                    ->properties(['text'])
                    ->withId()
            );

        $this->assertEquals('Second iteration!', $response['data']['Get'][$randomNamespace][0]['text'], 'Weaviate: New metadata incorrect');
    }

    /**
     * @depends test_it_can_create_vector
     *
     * @return void
     */
    public function test_it_can_delete_vectors()
    {
        $randomNamespace = ucfirst(fake()->word());

        /**
         * @var ObjectModel $response
         */
        $create = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
            ->instance()
            ->namespace($randomNamespace)
            ->create(
                WeaviateCreateRequest::build()
                    ->vector($this->vectorStoreUpdateTestEmbedding)
                    ->properties([
                        'text' => 'First iteration!',
                    ])
            );

        $this->assertEquals($create->getProperties()->get('text'), 'First iteration!', 'Weaviate: Failed to create a record');

        // delete
        $delete = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
            ->instance()
            ->namespace($randomNamespace)
            ->delete(
                WeaviateDeleteRequest::build()
                    ->id($create->getId())
            );

        $this->assertTrue($delete->successful(), 'Weaviate delete failed!');

        // check if the new metadata contains the new field
        $response = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
            ->instance()
            ->namespace($randomNamespace)
            ->query(
                WeaviateQueryRequest::build()
                    ->vector($this->vectorStoreUpdateTestEmbedding)
                    ->properties(['text'])
                    ->withId()
            );

        $this->assertEmpty($response['data']['Get'][$randomNamespace], 'Weaviate: Record not deleted');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
