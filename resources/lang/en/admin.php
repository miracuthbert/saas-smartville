<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/9/2018
 * Time: 11:19 PM
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain labels and messages used in
    | the admin panel. Feel free to tweak each of these messages here.
    |
    */

    'pages' => [
        'form' => [
            'labels' => [
                'title' => 'Title',
                'uri' => 'Uri',
                'name' => 'Name',
                'hidden' => 'Hidden',
                'template' => 'Template',
                'layout' => 'Layout',
                'order' => 'Page order',
                'body' => 'Body',
                'usable' => 'Usable',
            ],
            'text' => [
                'title' => "The display title of the page.",
                'uri' => "The uri is used to generate the page link. (eg. '/post/{id}/{slug}', '/contact', ...)",
                'name' => "The route name for the page.",
                'hidden' => "Check to hide page from navigation. Applicable to pages without children.",
                'template' => "The template to be used by page.",
                'layout' => "The theme layout to be used by page.",
                'order' => "The order by which page appears. &ast;Leave blank for default order.",
                'body' => "The page content.",
                'usable' => "Set whether page is live or not.",
            ],
        ],
    ],

    'tutorials' => [
        'form' => [
            'labels' => [
                'title' => 'Title',
                'overview' => 'Overview',
                'hidden' => 'Hidden',
                'template' => 'Template',
                'layout' => 'Layout',
                'order' => 'Page order',
                'body' => 'Body',
                'usable' => 'Usable',
            ],
            'text' => [
                'title' => "The title of the tutorial or step.",
                'overview' => "An overview of the tutorial.",
                'hidden' => "Check to hide tutorial from navigation. Applicable to tutorials without children.",
                'template' => "The template to be used by tutorial.",
                'layout' => "The theme layout to be used by tutorial.",
                'order' => "The order by which tutorial appears. &ast;Leave blank for default order.",
                'body' => "The tutorial or step description.",
                'usable' => "Set whether page is live or not.",
            ],
        ],
    ],
];