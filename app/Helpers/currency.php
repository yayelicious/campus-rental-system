<?php

if (!function_exists('peso')) {
    function peso($amount)
    {
        return '₱' . number_format($amount, 2);
    }
}
