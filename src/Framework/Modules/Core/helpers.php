<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Optional;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HigherOrderTapProxy;

if (! function_exists('mapping_get')) {
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * Seems to be suggested as function (https://github.com/laravel/ideas/issues/990)
     *
     * @param  mixed   $target
     * @param  string|array|int  $key
     * @param  mixed   $default
     * @return mixed
     */
    function mapping_get($target, $key, $default = null)
    {

        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);


        while (! is_null($segment = array_shift($key))) {
            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (! is_array($target)) {
                    return value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = data_get($item, $key);
                }

                return in_array('*', $key) ? Arr::collapse($result) : $result;
            }

            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } elseif (is_object($target) && method_exists($target, $segment) ) {
                $target = $target->$segment();
            } else {
                return value($default);
            }
        }

        return $target;
    }
}


if (! function_exists('array_remove_numeric_keys')) {

    /**
     * Removeds numeric value keys from array
     * @param $aArray
     * @return array
     */
    function array_remove_numeric_keys($aArray)
    {

        $aNewArray = [];
        foreach($aArray as $sKey => $sValue) {
            if (!is_numeric($sKey)) {
                $aNewArray[$sKey] = $sValue;
            }
        }

        return $aNewArray;

    }
}


if (! function_exists('str_replace_mb')) {

    /**
     * Removeds numeric value keys from array
     * @param $aArray
     * @return array
     */
    function str_replace_mb($search, $replace, $subject, &$count = 0)
    {

        if (!is_array($subject)) {
            // Normalize $search and $replace so they are both arrays of the same length
            $searches = is_array($search) ? array_values($search) : array($search);
            $replacements = is_array($replace) ? array_values($replace) : array($replace);
            $replacements = array_pad($replacements, count($searches), '');
            foreach ($searches as $key => $search) {
                $parts = mb_split(preg_quote($search), $subject);
                $count += count($parts) - 1;
                $subject = implode($replacements[$key], $parts);
            }
        } else {
            // Call mb_str_replace for each subject in array, recursively
            foreach ($subject as $key => $value) {
                $subject[$key] = mb_str_replace($search, $replace, $value, $count);
            }
        }
        return $subject;

    }
}

?>