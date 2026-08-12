<?php

namespace XShaan\Zohal\Exceptions;

use XShaan\Zohal\DTO\InquiryResult;

class ZohalApiException extends ZohalException
{
    public function __construct(
        string $message,
        public readonly ?InquiryResult $inquiry = null,
        public readonly ?int $status = null,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    public function resultCode(): ?int
    {
        return $this->inquiry?->result;
    }

    public function errorCode(): ?string
    {
        return $this->inquiry?->errorCode;
    }
}
