<?php

namespace AdrianTanase\VectorStore\Exceptions;

use RuntimeException;

class InvalidDatabaseAdapterRequestException extends RuntimeException
{
    protected $message = 'Invalid database adapter request provided. Please make sure the request you are using matches the VectorStoreProviderType.';
}
