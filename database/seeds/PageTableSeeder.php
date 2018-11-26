<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Smartville\Domain\Pages\Models\Page;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->truncate();

        $pages = [
            [
                'title' => 'Rental Property Manager',
                'uri' => '/',
                'name' => 'home',
                'template' => 'home',
                'layout' => 'plain',
                'usable' => true,
                'hidden' => true,
            ],
            [
                'title' => 'Features',
                'uri' => 'features',
                'name' => 'features.index',
                'template' => 'features',
                'layout' => 'plain',
                'usable' => true,
            ],
            [
                'title' => 'Pricing',
                'uri' => 'pricing',
                'name' => 'pricing.index',
                'template' => 'pricing',
                'layout' => 'plain',
                'usable' => true,
            ],
            [
                'title' => 'Documentation',
                'uri' => 'documentation',
                'name' => 'documentation.index',
                'template' => 'documentation.index',
                'layout' => 'plain',
                'usable' => true,
                'children' => [
                    [
                        'title' => 'Documentation article',
                        'uri' => 'documentation/{slug}',
                        'name' => 'documentation.show',
                        'template' => 'documentation.show',
                        'layout' => 'plain',
                        'usable' => true,
                        'hidden' => true,
                    ],
                ],
            ],
            [
                'title' => 'Support',
                'uri' => 'support',
                'name' => 'support.index',
                'template' => 'support.index',
                'layout' => 'plain',
                'usable' => true,
            ],
            [
                'title' => 'FAQ',
                'uri' => 'faq',
                'name' => 'faq',
                'template' => 'faq',
                'layout' => 'plain',
            ],
            [
                'title' => 'Blog',
                'uri' => 'blog',
                'name' => 'blog.index',
                'template' => 'blog.index',
                'layout' => 'app',
                'children' => [
                    [
                        'title' => 'Blog post',
                        'uri' => 'blog/{slug}',
                        'name' => 'blog.show',
                        'template' => 'blog.show',
                        'layout' => 'app',
                        'hidden' => true,
                    ],
                ],
            ],
            [
                'title' => 'About',
                'uri' => 'about',
                'name' => 'about',
                'template' => 'about',
                'layout' => 'plain',
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
        
//        factory(\Smartville\Domain\Pages\Models\Page::class)->create(
//            [
//                'title' => 'Developers',
//                'uri' => 'developers',
//                'name' => null,
//                'usable' => false,
//            ]
//        );
//
//        factory(\Smartville\Domain\Pages\Models\Page::class)->create(
//            [
//                'title' => 'API',
//                'uri' => 'api',
//                'name' => null,
//                'usable' => false,
//            ]
//        );

//        factory(\Smartville\Domain\Pages\Models\Page::class, 5)->create();
    }
}
