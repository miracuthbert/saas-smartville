<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 8/11/2018
 * Time: 10:59 PM
 */

return [
    'path' => [

        /**
         * Avatar
         */
        'avatar' => [
            'absolute' => public_path($relative = 'img/avatars'),
            'relative' => $relative
        ],

        /**
         * Properties
         */
        'properties' => [
            'absolute' => public_path($relative = 'img/properties'),
            'relative' => $relative
        ],
    ],
];