<?php

namespace AdrianTanase\VectorStore\Exceptions;

use AdrianTanase\VectorStore\Enums\VectorStoreProviderType;
use RuntimeException;
use Throwable;

class InvalidProviderException extends RuntimeException
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = sprintf('Invalid provider. Please use one of: %s', collect(VectorStoreProviderType::cases())->map(fn (VectorStoreProviderType $case) => $case->value)->implode(', '));
    }
}
