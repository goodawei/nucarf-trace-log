<?php

if (!function_exists('nucarf_trace_log')) {
    /**
     * nucarf trace log
     *
     * @param string $method
     * @param mixed|string $args
     * @param mixed $return
     * @param string|int|float $cost
     * @param \Throwable $exception
     */
    function nucarf_trace_log(string $method, $args, $return, $cost, Throwable $exception = null)
    {
        \Nucarf\TraceLog\TraceLog::method($method, $args, $return, $cost, $exception);
    }
}
