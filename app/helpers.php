<?php

use App\Models\SiteConfiguration;
use Illuminate\Database\Eloquent\Model;

if (! function_exists('id')) {
    /**
     * @param int|string|Model $value
     */
    function id($value = null)
    {
        if (is_null($value)) {
            return $value;
        }

        if (is_int($value) || is_string($value)) {
            return $value;
        }

        return $value->getKey();
    }
}

if (! function_exists('fields')) {
    function fields(?string $field = null)
    {
        if (is_null($field)) {
            return app('fields');
        }

        return app("fields.$field");
    }
}

if (! function_exists('criteria')) {
    function criteria(?string $criterion = null)
    {
        if (is_null($criterion)) {
            return app('criteria');
        }

        return app("criteria.$criterion");
    }
}

if (! function_exists('features')) {
    function features(?string $feature = null)
    {
        if (is_null($feature)) {
            return app()->tagged('features');
        }

        if (class_exists($feature)) {
            return app($feature);
        }

        return app("features.$feature");
    }
}

if (! function_exists('site')) {
    function site()
    {
        return app(SiteConfiguration::class);
    }
}
