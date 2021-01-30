<?php

if (! function_exists('resolve_classes')) {
    /**
     * Resolve an array of classes and conditions and return the final class string.
     *
     * @param  array  $classes
     * @return string
     */
    function resolve_classes($classes)
    {
        $result = [];

        foreach ($classes as $string => $condition) {
            if ($condition) {
                $result[] = trim($string);
            }
        }

        return trim(implode(' ', $result));
    }
}
