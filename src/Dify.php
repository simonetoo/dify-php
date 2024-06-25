<?php
declare(strict_types=1);

namespace Simoneto\Dify;

/**
 * @method static Factory setOption(string $key, $value)
 * @method static Factory setOptions(array $options)
 * @method static Factory setHeaders(array $headers)
 * @method static Factory setHeader(string $key, string $value)
 * @method static Factory setBaseUri(string $baseUri)
 * @method static Factory setMiddleware(callable $middleware)
 * @method static Client client(string $apiKey)
 */
class Dify
{
    /**
     * The default uri of dify.
     */
    const DEFAULT_BASE_URI = 'https://api.dify.ai';

    /**
     * @param string $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic(string $method, $parameters)
    {
        return call_user_func_array([Factory::make(), $method], $parameters);
    }
}
