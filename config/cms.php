<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/4/2018
 * Time: 2:04 PM
 *
 * Holds CMS settings.
 */

return [

    /**
     * This value determines the CMS Theme
     */
    'theme' => [
        'folder' => 'themes',
        'layout' => 'app',
        'active' => 'default',
    ],

    /**
     * The templates listed here are used to set a page's template
     */
    'templates' => [
        'home' => \Smartville\App\Templates\HomeTemplate::class,
        'features' => \Smartville\App\Templates\FeaturesTemplate::class,
        'pricing' => \Smartville\App\Templates\PricingTemplate::class,
        'documentation.index' => \Smartville\App\Templates\DocumentationTemplate::class,
        'documentation.show' => \Smartville\App\Templates\DocumentationShowTemplate::class,
        'support.index' => \Smartville\App\Templates\SupportTemplate::class,
    ],

];