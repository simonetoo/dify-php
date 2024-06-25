<?php
declare(strict_types=1);

namespace Simoneto\Dify;

use Simoneto\Dify\Apps\Chat;
use Simoneto\Dify\Apps\Completion;

/**
 * @method static Factory setOption(string $key, $value)
 * @method static Factory setOptions(array $options)
 * @method static Factory setHeaders(array $headers)
 * @method static Factory setHeader(string $key, string $value)
 * @method static Factory setBaseUri(string $baseUri)
 * @method static Factory setMiddleware(callable $middleware)
 * @method static Client create()
 * @method static Client createWithApiKey(string $apiKey)
 * @method static Chat chat(string $apiKey)
 * @method static Completion completion(string $apiKey)
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
