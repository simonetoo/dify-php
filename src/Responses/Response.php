<?php
declare(strict_types=1);

namespace Simoneto\Dify\Responses;

use GuzzleHttp\Psr7\MessageTrait;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Simoneto\Dify\DifyException;
use Simoneto\Dify\Utils;

class Response implements ResponseInterface
{

    /**
     * The PSR response.
     * @var ResponseInterface
     */
    protected $response;

    /**
     * The decoded JSON response.
     * @var array
     */
    protected $decoded;

    /**
     * Create a new response instance.
     *
     * @param ResponseInterface $baseResponse
     */
    public function __construct(ResponseInterface $baseResponse)
    {
        $this->response = $baseResponse;
    }

    /**
     * Get the PSR Response instance.
     *
     * @return ResponseInterface
     */
    public function getPsrResponse(): ResponseInterface
    {
        return $this->response;
    }

    /**
     * Get the body of the response.
     *
     * @return string
     */
    public function body(): string
    {
        return (string)$this->response->getBody();
    }

    /**
     * Get the JSON decoded body of the response as an array or scalar value.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    public function json(string $key = null, $default = null)
    {
        if (!$this->decoded) {
            $this->decoded = json_decode($this->body(), true);
        }

        if (is_null($key)) {
            return $this->decoded;
        }

        return Utils::arrayGet($this->decoded, $key, $default);
    }

    /**
     * Get the JSON decoded body of the response as an object.
     *
     * @return object|null
     */
    public function object(): ?object
    {
        return (object)$this->json();
    }

    /**
     *
     * @return bool
     */
    public function isHttpSuccessful(): bool
    {
        return $this->getStatusCode() === 200;
    }

    /**
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->isHttpSuccessful() && $this->json('code') === null;
    }

    /**
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return !$this->isSuccessful();
    }

    /**
     * Throw an exception if a http error occurred.
     *
     * @return $this
     */
    public function throwIfHttpFailed(): Response
    {
        if (!$this->isHttpSuccessful()) {
            throw new DifyException($this->json('message') ?? $this->getReasonPhrase(), $this->json('status') ?? $this->getStatusCode());
        }
        return $this;
    }

    /**
     * Throw an exception if a http or server error occurred.
     *
     * @return $this
     */
    public function throwIfFailed(): Response
    {
        if ($this->isFailed()) {
            throw new DifyException($this->json('message') ?? $this->getReasonPhrase(), $this->json('status') ?? $this->getStatusCode());
        }
        return $this;
    }

    /**
     * Get the http status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * @param int $code status code
     * @param string $reasonPhrase reason phrase
     * @return ResponseInterface
     */
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $this->response = $this->response->withStatus($code, $reasonPhrase);

        return $this;
    }

    /**
     * Gets the response reason phrase associated with the status code.
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }

    /**
     * @return string
     */
    public function getProtocolVersion(): string
    {
        return $this->response->getProtocolVersion();
    }

    /**
     * @param string $version
     * @return MessageInterface
     */
    public function withProtocolVersion(string $version): MessageInterface
    {
        $this->response = $this->response->withProtocolVersion($version);
        return $this;
    }

    /**
     * @return array|\string[][]
     */
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader(string $name): bool
    {
        return $this->response->hasHeader($name);
    }

    /**
     * @param string $name
     * @return array|string[]
     */
    public function getHeader(string $name): array
    {
        return $this->response->getHeader($name);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getHeaderLine(string $name): string
    {
        return $this->response->getHeaderLine($name);
    }

    /**
     * @param string $name
     * @param $value
     * @return MessageInterface
     */
    public function withHeader(string $name, $value): MessageInterface
    {
        $this->response = $this->withHeader($name, $value);
        return $this;
    }

    /**
     * @param string $name
     * @param $value
     * @return MessageInterface
     */
    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $this->response = $this->withAddedHeader($name, $value);
        return $this;
    }

    /**
     * @param string $name
     * @return MessageInterface
     */
    public function withoutHeader(string $name): MessageInterface
    {
        $this->response = $this->withoutHeader($name);
        return $this;
    }

    /**
     * @return StreamInterface
     */
    public function getBody(): StreamInterface
    {
        return $this->response->getBody();
    }

    /**
     * @param StreamInterface $body
     * @return MessageInterface
     */
    public function withBody(StreamInterface $body): MessageInterface
    {
        $this->response = $this->withBody($body);
        return $this;
    }
}
