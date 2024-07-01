<?php
declare(strict_types=1);

namespace Simonetoo\Dify;

use Throwable;

class DifyException extends \RuntimeException
{

    /**
     * The context of exception.
     *
     * @var array
     */
    public $context = [];

    /**
     * Create a new exception instance.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get yhe context of exception.
     *
     * @param $context
     * @param $value
     * @return $this
     */
    public function withContext($context, $value = null): DifyException
    {
        if (is_array($context)) {
            $this->context = array_merge($this->context, $context);
        } else if (is_string($context)) {
            $this->context[$context] = $value;
        }
        return $this;
    }
}
