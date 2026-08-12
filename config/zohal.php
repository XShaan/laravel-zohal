<?php

return [

    'token' => env('ZOHAL_TOKEN'),

    'base_url' => env('ZOHAL_BASE_URL', 'https://service.zohal.io/api/v0'),

    'timeout' => (int) env('ZOHAL_TIMEOUT', 30),

    'connect_timeout' => (int) env('ZOHAL_CONNECT_TIMEOUT', 10),

    'retry' => [
        'times' => (int) env('ZOHAL_RETRY_TIMES', 2),
        'sleep' => (int) env('ZOHAL_RETRY_SLEEP', 200),
    ],

    // نتیجه غیر از ۱ → ZohalApiException (تطابق false با result=1 خطا نیست)
    'throw' => (bool) env('ZOHAL_THROW', true),

];
