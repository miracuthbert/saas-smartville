<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 3/6/2018
 * Time: 6:16 PM
 *
 * Holds custom app settings
 */
return [

    /**
     * Cashier Settings
     */
    'cashier' => [
        'currency' => env('CASHIER_CURRENCY'),
        'symbol' => env('CASHIER_CURRENCY_SYMBOL'),
    ],

    /**
     * Auth Settings
     */
    'auth' => [
        'invitation' => [
            'token' => 200,
            'code' => 6,
            'expiry' => null
        ],
    ],

    /**
     * Timestamp Format Settings
     */
    'timestamp' => [
        'date_format' => 'Y-m-d',
        'time_format' => 'g:i a',
    ],
];