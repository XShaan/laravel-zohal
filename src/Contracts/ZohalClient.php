<?php

namespace XShaan\Zohal\Contracts;

use XShaan\Zohal\DTO\InquiryResult;

interface ZohalClient
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function post(string $path, array $payload = []): InquiryResult;

    public function get(string $path): InquiryResult;

    /**
     * Multipart upload (OCR / biometric media).
     *
     * @param  array<string, string|array{contents: string|resource, filename?: string}>  $files
     * @param  array<string, mixed>  $fields
     */
    public function upload(string $path, array $files, array $fields = []): InquiryResult;
}
