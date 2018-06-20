<?php namespace Zenodorus;

class Arrays
{
    /**
     * Recursive function to get things from deep in multi-dimensional
     * arrays.
     *
     * $directions should initially be an array (otherwise we wouldn't)
     * need this function. Each item in the array should be the key of
     * an element that is the child of the value returned by the preceeding
     * element.
     *
     * So with the following array:
     * ```
     * $array = [
     *      'top' => [
     *          'middle1' => 'hello',
     *          'middle2' => [
     *              'final' => 'this is it!'
     *          ]
     *      ]
     * ];
     * ```
     *
     * Calling `Arrays::pluck($array, ['top', 'middle2', 'final'])` would
     * return `this is it!`, while calling `Arrays::pluck($array, ['top', 'middle1'])`
     * would return `hello`.
     *
     * Setting $safe to `true` can cause unexpected behavior: Since it returns
     * `null` when it fails, it could theoretically return lead a user to
     * believe they had successfully retrieved an array item that had the
     * explicity value of `null`. It is therefore recommended to keep $safe off.
     *
     * @param array            $array         The array to pluck from.
     * @param array|string|int $directions    Directions to the element we want.
     * @param boolean          $safe          Whether or not to throw an error.
     * @return mixed                          Returns whatever the value is (can
     *                                        be anything).
     */
    public static function pluck(array $array, $directions, $safe = false)
    {
        if ((is_string($directions)
                || is_int($directions))
            && isset($array[$directions])) {
            // If $directions is a key, just return the value for that key.
            return $array[$directions];
        } elseif (is_array($directions)) {
            // If $directions isn't a key, then we have more work to do.
            if (count($directions) === 1) {
                // If $directions has only one value, call array_pluck()
                // with that one value as the key.
                return static::pluck($array, $directions[0], $safe);
            } elseif (isset($array[$directions[0]])) {
                // If $directions is still a multi-value array, then we
                // have more work to do. Get rid of the direction we're
                // on, and start recursing.
                $key = array_shift($directions);
                return static::pluck($array[$key], $directions, $safe);
            }
        }

        if ($safe === true) {
            return null;
        } else {
            return new ZenodorusError([
                'code'        => "pluck::not-found",
                'description' => "Arrays::pluck() could not find the value 
                    you're looking for.",
                'data'        => ['array' => $array, 'directions' => $directions],
            ]);
        }
    }

    /**
     * Flatten a multidimensional array.
     *
     * @see https://stackoverflow.com/a/1320112
     * @param array|mixed $array
     * @return array
     */
    public static function flatten($array)
    {
        if (!is_array($array)) {
            // nothing to do if it's not an array
            return array($array);
        }

        $result = array();
        foreach ($array as $value) {
            // explode the sub-array, and add the parts
            $result = array_merge($result, static::flatten($value));
        }

        return $result;
    }

    /**
     * Tests if an array is empty.
     *
     * "Empty" here means that every element contains either an
     * empty string, or the value `null`. Other values that would
     * be considered `empty` by the `empty()` function are *not*
     * considered as such here. That means that the array
     * ```
     * ['', null, false]
     * ```
     * is *not* empty.
     *
     * @param array $array
     * @return boolean
     */
    public static function isEmpty(array $array)
    {
        $flattened = static::flatten($array);

        foreach ($flattened as $item) {
            if (null === $item || '' === $item) {
                continue;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * This removes all falsey values from an array.
     *
     * By default `null` evaluates as falsey. To include `null` values, pass `true` to the second argument.
     *
     * @param array $array
     * @param bool  $treatNullAsTrue
     * @return array
     */
    public static function compact(array $array, bool $treatNullAsTrue = false)
    {
        $compacted = array();
        foreach ($array as $key => $field) {
            if (true === $treatNullAsTrue) {
                if ($field || null === $field) {
                    $compacted[$key] = $field;
                }
            } else {
                if ($field) {
                    $compacted[$key] = $field;
                }
            }
        }

        return $compacted;
    }

    /**
     * Remove items from an array based on their value. Keys are not changed.
     *
     * **Does not recurse**, so be wary of using it with multidimentional
     * arrays.
     *
     * The values passed to `$values` can be anything that is a valid array
     * value, but they're matched using `in_array` with `strict = true`.
     *
     * @param array $array
     * @param mixed ...$values
     * @return array
     */
    public static function removeByValue(array $array, ...$values)
    {
        foreach ($array as $key => $value) {
            if (in_array($value, $values, true)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * array_map with keys OR array_walk but functional
     *
     * The called function receives two arguments: `$key` & `$value`.
     *
     * If the called function returns an array with a single value, that single value will be inserted into the new
     * array at the position of the original key.
     *
     * If the called function returns an array with two values, the first value used as a *new* key in the new array,
     * and second value is used as the value in the new array.
     *
     * **WARNING**
     *
     * And returns value other than an array with one or two values will be ignored! This can be useful (i.e. to
     * clean up an array) but you should be aware of it.
     *
     * @param callable $function
     * @param array    $array
     * @return array
     */
    public static function mapKeys(callable $function, array $array)
    {
        $new_array = array();
        foreach ($array as $key => $value) {
            $result = call_user_func($function, $value, $key);
            if (is_array($result)) {
                if (1 === count($result)) {
                    // Only returned a value, no key, so keep existing key.
                    $new_array[$key] = array_shift($result);
                } elseif (2 === count($result)) {
                    // Returned a key and a value, so set a new key.
                    $new_array[array_shift($result)] = array_shift($result);
                }
            }
            unset($result);
        }
        return $new_array;
    }
}
