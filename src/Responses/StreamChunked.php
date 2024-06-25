<?php
declare(strict_types=1);

namespace Simoneto\Dify\Responses;

class StreamChunked
{
    /**
     * The event name of the data.
     *
     * @var string
     */
    public $event;

    public $data;

    /**
     * @param array $chunk
     */
    public function __construct(array $chunk)
    {
        $this->event = $chunk['event'];
        $this->data = $chunk;
    }
}
