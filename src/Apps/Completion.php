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
     * @param array $parameters
     * @return Response
     */
    public function send(array $parameters): Response
    {
        return $this->client->postJson('/completion-messages', array_merge($parameters, [
            'response_mode' => 'blocking',
        ]));
    }

    /**
     * Send a request to the chat application with enable streaming mode.
     *
     * @param array $parameters
     * @return StreamResponse
     */
    public function stream(array $parameters): StreamResponse
    {
        $response = $this->client->postJson('/completion-messages', array_merge($parameters, [
            'response_mode' => 'streaming',
        ]))->throwIfHttpFailed();
        return new StreamResponse($response);
    }

    /**
     * Stop generate
     * Only supported in streaming mode.
     *
     * @param string $taskId
     * @param string $userid
     * @return Response
     */
    public function stop(string $taskId, string $userid): Response
    {
        return $this->client->postJson("/completion-messages/{$taskId}/stop", [
            'user' => $userid
        ]);
    }
}
