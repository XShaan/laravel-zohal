<?php

namespace XShaan\Zohal\DTO;

class InquiryResult
{
    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly int $result,
        public readonly ?bool $matched,
        public readonly ?string $message,
        public readonly ?string $errorCode,
        public readonly array $data,
        public readonly array $raw,
        public readonly int $status,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function fromResponse(array $payload, int $status = 200): self
    {
        $body = $payload['response_body'] ?? null;

        if (is_array($body)) {
            $data = is_array($body['data'] ?? null) ? $body['data'] : [];
            $message = isset($body['message']) ? (string) $body['message'] : null;
            $errorCode = isset($body['error_code']) && $body['error_code'] !== null
                ? (string) $body['error_code']
                : null;
            $matched = self::extractMatched($data, $body);
        } elseif (array_key_exists('result', $payload)) {
            $data = is_array($payload['data'] ?? null) ? $payload['data'] : [];
            $message = isset($payload['message']) ? (string) $payload['message'] : null;
            $errorCode = isset($payload['error_code']) && $payload['error_code'] !== null
                ? (string) $payload['error_code']
                : null;
            $matched = self::extractMatched($data, $payload);
        } else {
            // Non-standard payloads (credit OTP, biometric session, …)
            $data = $payload;
            $message = isset($payload['message']) ? (string) $payload['message'] : null;
            $errorCode = isset($payload['error_code']) && $payload['error_code'] !== null
                ? (string) $payload['error_code']
                : null;
            $matched = self::extractMatched($data, $payload);
        }

        $resultCode = array_key_exists('result', $payload)
            ? (int) $payload['result']
            : ($status >= 200 && $status < 300 ? 1 : 0);

        return new self(
            result: $resultCode,
            matched: $matched,
            message: $message,
            errorCode: $errorCode,
            data: $data,
            raw: $payload,
            status: $status,
        );
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $source
     */
    protected static function extractMatched(array $data, array $source): ?bool
    {
        if (array_key_exists('matched', $data)) {
            return (bool) $data['matched'];
        }

        if (array_key_exists('matched', $source)) {
            return (bool) $source['matched'];
        }

        return null;
    }

    public function ok(): bool
    {
        return $this->result === 1;
    }

    public function isMatched(): bool
    {
        return $this->ok() && $this->matched === true;
    }

    /**
     * @return array{success: bool, matched: bool, message: string|null, error_code: string|null, data: array<string, mixed>, provider: string}
     */
    public function toArray(): array
    {
        return [
            'success' => $this->ok(),
            'matched' => $this->isMatched(),
            'message' => $this->message,
            'error_code' => $this->errorCode,
            'data' => $this->data,
            'provider' => 'zohal',
        ];
    }
}
