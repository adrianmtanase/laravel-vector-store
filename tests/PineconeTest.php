<?php

namespace AdrianTanase\VectorStore\Test;

use AdrianTanase\VectorStore\Facades\VectorStore;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeGetRequest;
use AdrianTanase\VectorStore\Providers\Pinecone\Requests\PineconeUpsertRequest;

class PineconeTest extends TestCase
{
	private array $vectorIsLitEmbedding;

	protected function setUp(): void
	{
		parent::setUp();

		$this->vectorIsLitEmbedding = json_decode(file_get_contents('tests/ada-002-vector-store-is-lit-embedding.json'));
	}

	public function test_it_can_upsert_to_pinecone() {
		VectorStore::dataset('vector-store')
			->namespace('general')
			->upsert(
				PineconeUpsertRequest::initialize()
					->id('1')
					->values($this->vectorIsLitEmbedding)
					->metadata([
						'text' => 'Vector store is lit!'
					])
            );
	}

	public function test_it_can_get_vectors_pinecone() {
		$response = VectorStore::dataset('vector-store')
			->namespace('general')
			->get(
				PineconeGetRequest::initialize()
					->ids(['1'])
			);

		$this->assertTrue($response->ok());
	}

	protected function tearDown(): void
	{
		parent::tearDown();
	}
}