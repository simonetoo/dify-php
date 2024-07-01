<?php
declare(strict_types=1);

namespace Simonetoo\Dify;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use Simonetoo\Dify\Responses\Response;

class Client
{
    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @param GuzzleClient $client
     */
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send a request.
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return Response
     * @throws DifyException
     */
    public function request(string $method, string $uri, array $options = []): Response
    {
        try {
            $response = $this->client->request($method, $uri, $options);
            return new Response($response);
        } catch (ClientException $exception) {
            if ($exception->hasResponse()) {
                return new Response($exception->getResponse());
            }
            throw new DifyException($exception->getMessage(), $exception->getCode(), $exception);
        } catch (\Throwable $exception) {
            throw new DifyException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * Send a get request.
     *
     * @param string $uri
     * @param array $query
     * @param array $options
     * @return Response
     */
    public function get(string $uri, array $query = [], array $options = []): Response
    {
        $options['query'] = $query;
        return $this->request('GET', $uri, $options);
    }

    /**
     * Send a post request with form-data.
     *
     * @param string $uri
     * @param array $data
     * @param array $options
     * @return Response
     */
    public function postForm(string $uri, array $data = [], array $options = []): Response
    {
        $options['form_params'] = $data;
        return $this->request('POST', $uri, $options);
    }

    /**
     * Send a post request with json.
     *
     * @param string $uri
     * @param array $data
     * @param array $options
     * @return Response
     */
    public function postJson(string $uri, array $data = [], array $options = []): Response
    {
        $options['json'] = $data;
        return $this->request('POST', $uri, $options);
    }
}
