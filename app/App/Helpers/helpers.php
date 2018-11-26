<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 12/29/2017
 * Time: 1:30 AM
 */

use Carbon\Carbon;

if (!function_exists('on_page')) {
    /**
     * Check's whether request url/route matches passed link
     *
     * @param $path
     * @param string $type
     * @return null
     */
    function on_page($path, $type = "name")
    {
        switch ($type) {
            case "url":
                $result = ($path == request()->is($path));
                break;

            default:
                $result = ($path == request()->route()->getName());
        }

        return $result;
    }
}

if (!function_exists('exists_in_filter_key')) {
    /**
     * Appends passed value if condition is true
     *
     * @param $key
     * @param $value
     * @return null
     */
    function exists_in_filter_key($key, $value = null)
    {
        return collect(explode("&", $key))->contains($value);
    }
}

if (!function_exists('join_in_filter_key')) {
    /**
     * Appends passed value if condition is true
     *
     * @param $value
     * @return null
     */
    function join_in_filter_key(...$value)
    {
        //remove empty values
        $value = array_filter($value, function ($item) {
            return isset($item);
        });

        return collect($value)->implode('+');
    }
}

if (!function_exists('remove_from_filter_key')) {
    /**
     * Appends passed value if condition is true
     *
     * @param $key
     * @param $oldValues
     * @param $value
     * @return null
     */
    function remove_from_filter_key($key, $oldValues, $value)
    {
        $newValues = array_diff(
            array_values(
                explode("+", $oldValues)
            ), [
            $value, 'page'
        ]);

        if (count($newValues) == 0) {
            array_except(request()->query(), [$key, 'page']);

            return null;
        }

        return collect($newValues)->implode('+');
    }
}

if (!function_exists('return_if')) {
    /**
     * Appends passed value if condition is true
     *
     * @param $condition
     * @param $value
     * @return null
     */
    function return_if($condition, $value)
    {

        if ($condition) {
            return $value;
        }
    }
}

if (!function_exists('page_title')) {
    /**
     * Returns page title from passed values
     *
     * @param $title
     * @param $separator
     * @return null
     */
    function page_title($title, $separator = '-')
    {
        return "{$title} {$separator} ";
    }
}

if (!function_exists('dateParse')) {
    /**
     * Create a new Carbon instance for the given time.
     *
     * @param $time
     * @param  \DateTimeZone|string|null $tz
     * @return \Illuminate\Support\Carbon
     */
    function dateParse($time, $tz = null)
    {
        return Carbon::parse($time, $tz);
    }
}

if (!function_exists('invoiceContext')) {
    /**
     * Apply a contextual class to based on invoice status.
     *
     * @param $invoice
     * @param string $context
     * @param null $default
     * @param bool $override
     * @return string
     */
    function invoiceContext($invoice, $context = "table", $default=null, $override = false)
    {
        // draft
        if (!$invoice->sent_at) {
            return $override == true ? $context : "{$context}-light";
        }

        // sent
        if (!$invoice->cleared_at && Carbon::now()->lt($invoice->sent_at)) {
            return $override == true ? $context : "{$context}-info";
        }

        // due between 1 to 7 days period
        if (
            !$invoice->cleared_at &&
            Carbon::now()->lte($invoice->due_at) &&
            (Carbon::now()->diffInDays($invoice->due_at) >= 1 && Carbon::now()->diffInDays($invoice->due_at) <= 7)
        ) {
            return "{$context}-warning";
        }

        // due today
        if (!$invoice->cleared_at && Carbon::now()->isSameDay($invoice->due_at)) {
            return $override == true ? $context : "{$context}-warning";
        }

        // past due
        if (
            !$invoice->cleared_at &&
            Carbon::now()->gt($invoice->due_at) &&
            Carbon::now()->diffInDays($invoice->due_at) > 0
        ) {
            return $override == true ? $context : "{$context}-danger";
        }

        // cleared
        if ($invoice->cleared_at) {
            return $override == true ? $context : "{$context}-success";
        }

        return $default;
    }
}
