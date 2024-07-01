<?php
declare(strict_types=1);

namespace Simonetoo\Dify;


use ArrayAccess;
use Closure;

class Utils
{

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param $array
     * @return array
     */
    public static function arrayCollapse($array): array
    {
        $results = [];

        foreach ($array as $values) {
            if (!is_array($values)) {
                continue;
            }

            $results[] = $values;
        }

        return array_merge([], ...$results);
    }

    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param $target
     * @param $key
     * @param $default
     * @return array|mixed
     */
    public static function arrayGet($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        foreach ($key as $i => $segment) {
            unset($key[$i]);

            if (is_null($segment)) {
                return $target;
            }

            if ($segment === '*') {
                if (!is_iterable($target)) {
                    return static::value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = static::arrayGet($item, $key);
                }

                return in_array('*', $key) ? static::arrayCollapse($result) : $result;
            }

            if ((is_array($target) && array_key_exists($segment, $target)) || ($target instanceof ArrayAccess && $target->offsetExists($segment))) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return static::value($default);
            }
        }

        return $target;
    }

    /**
     * Return the default value of the given value.
     *
     * @param $value
     * @param ...$args
     * @return mixed
     */
    public static function value($value, ...$args)
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}
