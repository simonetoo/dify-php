<?php
declare(strict_types=1);

namespace Simoneto\Dify\Apps;

use Simoneto\Dify\Responses\Response;
use Simoneto\Dify\Responses\StreamResponse;

class Chat extends App
{
    /**
     * Get the app meta information.
     *
     * @param string $userId
     * @return Response
     */
    public function meta(string $userId): Response
    {
        return $this->client->get('meta', [
            'user' => $userId
        ]);
    }

    /**
     * Stop generate
     * Only supported in streaming mode.
     *
     * @param string $taskId
     * @param string $userId
     * @return Response
     */
    public function stop(string $userId, string $taskId): Response
    {
        return $this->client->postJson('chat-message/' . $taskId . '/stop', [
            'user' => $userId
        ]);
    }

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
        return $this->client->postJson('chat-messages', array_merge($parameters, [
            'user' => $userId,
            'query' => $query,
            'inputs' => [],
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
        $response = $this->client->postJson('chat-messages', array_merge($parameters, [
            'user' => $userId,
            'query' => $query,
            'inputs' => [],
            'response_mode' => 'streaming',
        ]))->throwIfHttpFailed();
        return new StreamResponse($response);
    }

    /**
     * Get next questions suggestions for the current message.
     *
     * @param string $userId
     * @param string $messageId
     * @return Response
     */
    public function suggested(string $userId, string $messageId): Response
    {
        return $this->client->postJson("messages/{$messageId}/suggested", [
            'user' => $userId
        ]);
    }

    /**
     * Get conversation history messages.
     *
     * @param string $userId
     * @param string $conversationId
     * @param array $parameters
     * @return Response
     */
    public function messages(string $userId, string $conversationId, array $parameters = []): Response
    {
        return $this->client->postJson('messages', array_merge($parameters, [
            'conversation_id' => $conversationId,
            'user' => $userId,
        ]));
    }

    /**
     * Get conversations.
     *
     * @param string $userId
     * @param array $parameters
     * @return Response
     */
    public function conversations(string $userId, array $parameters = []): Response
    {
        return $this->client->postJson('conversations', array_merge($parameters, [
            'user' => $userId,
        ]));
    }

    /**
     * Conversation rename.
     *
     * @param string $userId
     * @param string $conversationId
     * @param string $name
     * @return Response
     */
    public function conversationRename(string $userId, string $conversationId, string $name): Response
    {
        return $this->client->postJson("conversations/{$conversationId}/name", [
            'name' => $name,
            'user' => $userId
        ]);
    }

    /**
     * Automatically generate the conversation name.
     *
     * @param string $userId
     * @param string $conversationId
     * @return Response
     */
    public function conversationAutoGenerateName(string $userId, string $conversationId): Response
    {
        return $this->client->postJson("conversations/{$conversationId}/name", [
            'auto_generate' => true,
            'user' => $userId
        ]);
    }

    /**
     * Delete a conversation.
     *
     * @param string $userId
     * @param string $conversationId
     * @return Response
     */
    public function conversationDelete(string $userId, string $conversationId): Response
    {
        return $this->client->request('DELETE', "conversations/{$conversationId}", [
            'query' => [
                'user' => $userId
            ]
        ]);
    }

    /**
     * Speech to text.
     *
     * @param string $userId
     * @param string $filePath
     * @return Response
     */
    public function audioToText(string $userId, string $filePath): Response
    {
        return $this->client->request('POST', 'audio-to-text', [
            [
                'name' => 'file',
                'contents' => fopen($filePath, 'r+')
            ], [
                'name' => 'user',
                'contents' => $userId
            ]
        ]);
    }
}
