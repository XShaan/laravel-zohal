<?php

namespace XShaan\Zohal;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use XShaan\Zohal\Contracts\ZohalClient;
use XShaan\Zohal\DTO\InquiryResult;
use XShaan\Zohal\Exceptions\ZohalApiException;
use XShaan\Zohal\Exceptions\ZohalConfigurationException;
use XShaan\Zohal\Exceptions\ZohalException;
use XShaan\Zohal\Support\ErrorMessages;

class Client implements ZohalClient
{
    public function __construct(
        protected string $token,
        protected string $baseUrl = 'https://service.zohal.io/api/v0',
        protected int $timeout = 30,
        protected int $connectTimeout = 10,
        protected int $retryTimes = 2,
        protected int $retrySleep = 200,
        protected bool $throw = true,
    ) {
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function post(string $path, array $payload = []): InquiryResult
    {
        return $this->send('post', $path, $payload);
    }

    public function get(string $path): InquiryResult
    {
        return $this->send('get', $path);
    }

    /**
     * @param  array<string, string|array{contents: string|resource, filename?: string}>  $files
     * @param  array<string, mixed>  $fields
     */
    public function upload(string $path, array $files, array $fields = []): InquiryResult
    {
        $this->guardToken();

        $request = $this->http(json: false)->asMultipart();

        foreach ($files as $name => $file) {
            if (is_array($file)) {
                $request = $request->attach(
                    $name,
                    $file['contents'],
                    $file['filename'] ?? $name,
                );
            } else {
                $contents = is_file($file) ? fopen($file, 'r') : $file;
                $filename = is_file($file) ? basename($file) : $name;
                $request = $request->attach($name, $contents, $filename);
            }
        }

        foreach ($fields as $key => $value) {
            $request = $request->attach($key, (string) $value);
        }

        try {
            $response = $request->post($this->url($path));
        } catch (ConnectionException $e) {
            throw new ZohalException(ErrorMessages::unreachable($e->getMessage()), 0, $e);
        }

        return $this->hydrate($response);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function send(string $method, string $path, array $payload = []): InquiryResult
    {
        $this->guardToken();

        try {
            $response = $method === 'get'
                ? $this->http()->get($this->url($path))
                : $this->http()->post($this->url($path), $payload);
        } catch (ConnectionException $e) {
            throw new ZohalException(ErrorMessages::unreachable($e->getMessage()), 0, $e);
        }

        return $this->hydrate($response);
    }

    protected function hydrate(Response $response): InquiryResult
    {
        $json = $response->json();
        if (! is_array($json)) {
            $decoded = json_decode($response->body(), true);
            $json = is_array($decoded) ? $decoded : [];
        }

        $result = InquiryResult::fromResponse($json, $response->status());

        if ($this->throw && ! $result->ok()) {
            throw new ZohalApiException(
                ErrorMessages::forResult($result->result, $result->message, $result->errorCode),
                $result,
                $response->status(),
            );
        }

        return $result;
    }

    protected function guardToken(): void
    {
        if ($this->token === '') {
            throw new ZohalConfigurationException(ErrorMessages::missingToken());
        }
    }

    protected function url(string $path): string
    {
        return rtrim($this->baseUrl, '/').'/'.ltrim($path, '/');
    }

    protected function http(bool $json = true): PendingRequest
    {
        $request = Http::withToken($this->token)
            ->acceptJson()
            ->timeout($this->timeout)
            ->connectTimeout($this->connectTimeout);

        if ($json) {
            $request = $request->asJson();
        }

        if ($this->retryTimes > 0) {
            $request = $request->retry($this->retryTimes, $this->retrySleep, throw: false);
        }

        return $request;
    }
}
