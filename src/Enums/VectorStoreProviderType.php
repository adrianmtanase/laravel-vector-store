<?php

namespace AdrianTanase\VectorStore\Enums;

enum VectorStoreProviderType: string
{
    case PINECONE = 'PINECONE';
    case WEAVIATE = 'WEAVIATE';
}
