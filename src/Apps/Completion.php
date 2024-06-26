<?php
declare(strict_types=1);

namespace Simoneto\Dify\Apps;

use Simoneto\Dify\Responses\Response;
use Simoneto\Dify\Responses\StreamResponse;

class Completion extends App
{

    /**
     * Send a request to the chat application.
     *
     * @param string $userId
     * @param string $query
     * @param array $parameters
     * @return Response
     */
    public function send(string $userId, string $query, array $parameters = []): Response
    {
        $parameters['inputs']['query'] = $query;
        return $this->client->postJson('completion-messages', array_merge($parameters, [
            'user' => $userId,
            'response_mode' => 'blocking',
        ]));
    }

    /**
     * Send a request to the chat application with enable streaming mode.
     *
     * @param string $userId
     * @param string $query
     * @param array $parameters
     * @return StreamResponse
     */
    public function stream(string $userId, string $query, array $parameters): StreamResponse
    {
        $parameters['inputs']['query'] = $query;
        $response = $this->client->postJson('completion-messages', array_merge($parameters, [
            'user' => $userId,
            'response_mode' => 'streaming',
        ]))->throwIfHttpFailed();
        return new StreamResponse($response);
    }

    /**
     * Stop generate
     * Only supported in streaming mode.
     *
     * @param string $userId
     * @param string $taskId
     * @return Response
     */
    public function stop(string $userId, string $taskId): Response
    {
        return $this->client->postJson("completion-messages/{$taskId}/stop", [
            'user' => $userId
        ]);
    }
}
