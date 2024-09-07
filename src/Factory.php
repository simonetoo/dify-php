<?php
declare(strict_types=1);

namespace Simonetoo\Dify;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use Simonetoo\Dify\Apps\Chat;
use Simonetoo\Dify\Apps\Completion;
use Simonetoo\Dify\Apps\Dataset;

class Factory
{
    /**
     * The guzzle http client options.
     *
     * @var array
     */
    protected $options = [
        'base_uri' => Dify::DEFAULT_BASE_URI,
        'headers' => []
    ];

    /**
     * The guzzle http client middlewares.
     *
     * @var array<int,callable>
     */
    protected $middlewares = [];

    /**
     *
     * @var Factory
     */
    protected static $instance;

    /**
     * Get the single instance of factory.
     *
     * @return Factory
     */
    public static function make(): Factory
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     *
     */
    private function __construct()
    {

    }

    /**
     * Set a guzzle http client option.
     *
     * @param string $key
     * @param $value
     * @return $this
     */
    public function setOption(string $key, $value): Factory
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * Set guzzle http client options.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): Factory
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    /**
     * Set guzzle http client headers.
     *
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers): Factory
    {
        $this->options['headers'] = array_merge($this->options['header'], $headers);
        return $this;
    }

    /**
     * Set a guzzle http client header.
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setHeader(string $key, string $value): Factory
    {
        $this->options['headers'][$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function removeHeader(string $key): Factory
    {
        unset($this->options['headers'][$key]);
        return $this;
    }

    /**
     * Set the dify base uri.
     *
     * @param string $baseUri
     * @return $this
     */
    public function setBaseUri(string $baseUri): Factory
    {
        return $this->setOption('base_uri', $baseUri);
    }

    /**
     * Set a middleware for guzzle http client.
     *
     * @param callable $middleware
     * @return $this
     */
    public function setMiddleware(callable $middleware): Factory
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * Create a new client.
     *
     * @return Client
     */
    public function create(): Client
    {
        $handler = $this->options['handler'] ?? HandlerStack::create();
        foreach ($this->middlewares as $middleware) {
            $handler->push($middleware);
        }
        return new Client(new GuzzleClient($this->options));
    }

    /**
     * Create a new client with api key.
     * @param string $apiKey
     * @return Client
     */
    public function createWithApiKey(string $apiKey): Client
    {
        $authorization = $this->options['headers']['Authorization'] ?? [];
        $this->options['headers']['Authorization'] = 'Bearer ' . $apiKey;
        $client = $this->create();
        if (!empty($authorization)) {
            $this->options['headers']['Authorization'] = $authorization;
        }
        return $client;
    }

    /**
     * Create a chat app.
     *
     * @param string $apiKey
     * @return Chat
     */
    public function chat(string $apiKey): Chat
    {
        return new Chat($this->createWithApiKey($apiKey));
    }

    /**
     * Create a completion app.
     *
     * @param string $apiKey
     * @return Completion
     */
    public function completion(string $apiKey): Completion
    {
        return new Completion($this->createWithApiKey($apiKey));
    }
    /**
     * Create a dataset app.
     *
     * @param string $apiKey
     * @return Dataset
     */
    public function dataset(string $apiKey): Dataset
    {
        return new Dataset($this->createWithApiKey($apiKey));
    }
}
