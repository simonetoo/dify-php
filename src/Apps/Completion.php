<?php
declare(strict_types=1);

namespace Simonetoo\Dify\Apps;

use Simonetoo\Dify\Responses\Response;
use Simonetoo\Dify\Responses\StreamResponse;

class Completion extends App
{

    /**
     * Send a request to the chat application.
     *
     * @param string $userId
     * @param array $parameters
     * @return Response
     */
    public function send(string $userId, array $parameters = []): Response
    {
        return $this->client->postJson('completion-messages', array_merge([
            'inputs' => [],
        ], $parameters, [
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
    public function stream(string $userId, array $parameters = []): StreamResponse
    {
        $response = $this->client->postJson('completion-messages', array_merge([
            'inputs' => [],
        ], $parameters, [
            'user' => $userId,
            'response_mode' => 'streaming',
        ]), ['stream' => true])->throwIfHttpFailed();
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
