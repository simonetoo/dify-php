<?php
declare(strict_types=1);

namespace Simoneto\Dify\Responses;

use Generator;
use IteratorAggregate;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class StreamResponse implements IteratorAggregate
{
    /**
     * The PSR response.
     *
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator(): Generator
    {
        while (!$this->response->getBody()->eof()) {
            $line = $this->readLine($this->response->getBody());

            if (strpos($line, 'data:') !== 0) {
                continue;
            }

            $data = trim(substr($line, strlen('data:')));
            $result = json_decode($data, true, JSON_THROW_ON_ERROR);

            yield new StreamChunked($result);
        }
    }

    /**
     * Read a line from the stream.
     */
    private function readLine(StreamInterface $stream): string
    {
        $buffer = '';

        while (!$stream->eof()) {
            if ('' === ($byte = $stream->read(1))) {
                return $buffer;
            }
            $buffer .= $byte;
            if ($byte === "\n") {
                break;
            }
        }

        return $buffer;
    }
}
