<?php

namespace AdrianTanase\VectorStore\Test;

use AdrianTanase\VectorStore\Enums\VectorStoreProviderType;
use AdrianTanase\VectorStore\Facades\VectorStore;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateCreateRequest;
use AdrianTanase\VectorStore\Providers\Weaviate\Requests\WeaviateQueryRequest;
use AdrianTanase\VectorStore\Providers\Weaviate\Serializable\QueryParameters\WeaviateQueryParameters;
use Weaviate\Collections\ObjectCollection;
use Weaviate\Model\ObjectModel;

class WeaviateTest extends TestCase
{
	private array $vectorIsLitEmbedding;

	protected function setUp(): void
	{
		parent::setUp();

		$this->vectorIsLitEmbedding = json_decode(file_get_contents('tests/ada-002-vector-store-is-lit-embedding.json'), true);
	}

	public function test_it_can_batch_create_vector_in_weaviate() {
		/**
		 * @var ObjectCollection $response
		 */
		$response = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
			->dataset('vector-store')
			->namespace('general')
			->batchCreate(
				[
					WeaviateCreateRequest::build()
						->vector($this->vectorIsLitEmbedding)
						->properties([
							'text' => 'Vector store has been batch created 1!'
						]),
					WeaviateCreateRequest::build()
						->vector($this->vectorIsLitEmbedding)
						->properties([
							'text' => 'Vector store has been batch created 1!'
						])
				]
		   );

		$this->assertEquals($response->first()['properties']['text'], 'Vector store has been batch created 1!', 'Failed to create batch records in Weaviate!');
	}

	public function test_it_can_create_vector_in_weaviate() {
		/**
		 * @var ObjectModel $response
		 */
		$response = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
			->dataset('vector-store')
			->namespace('general')
			->create(
				WeaviateCreateRequest::build()
					->vector($this->vectorIsLitEmbedding)
					->properties([
						'text' => 'Vector store is lit!'
					])
            );

		$this->assertEquals($response->getProperties()->get('text'), 'Vector store is lit!', 'Failed to create a record in Weaviate!');
	}

	/**
	 * @depends test_it_can_create_vector_in_weaviate
	 * @return void
	 */
	public function test_it_can_query_vectors_in_weaviate() {
		$response = VectorStore::provider(VectorStoreProviderType::WEAVIATE)
			->dataset('vector-store')
			->namespace('general')
			->query(
				WeaviateQueryRequest::build()
					->vector($this->vectorIsLitEmbedding)
					->properties(['text'])
					->withParameters(WeaviateQueryParameters::build()->group('type: closest, force: 1'))
			);

		$this->assertEquals('Vector store is lit!', $response['data']['Get']['General'][0]['text'], 'Weaviate vector query wrong!');
	}

	protected function tearDown(): void
	{
		parent::tearDown();
	}
}