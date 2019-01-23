<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 1/20/2019
 * Time: 10:13 PM
 */

namespace Smartville\App\Settings;

use Illuminate\Support\Facades\File;
use Spatie\Valuestore\Valuestore;

class TenantRentSettings extends Valuestore
{
    /**
     * The default tenant rent settings.
     *
     * @var array
     */
    public static $defaults = [
        'auto-generate' => false,
        'billing-interval' => 'monthly',
        'billing-cycle' => 1,
        'billing-day' => 30,
        'due-day' => 7,
        'invoice-template' => 'default',
    ];

    /**
     * Rent settings map.
     *
     * @return array
     */
    public static function map()
    {
        $map = [
            'auto-generate' => [
                'label' => 'Auto generate invoices',
                'values' => [
                    'true' => 'Yes',
                    'false' => 'No (You will only be notified)'
                ],
            ],
            'billing-interval' => [
                'label' => 'Billing interval',
                'values' => [   // todo: fetch from property or lease model
                    'monthly' => 'Monthly',
                ],
            ],
            'billing-cycle' => [
                'label' => 'Billing cycle',
                'values' => null,
                'type' => 'number',
            ],
            'billing-day' => [
                'label' => 'Billing day',
                'values' => null,
                'type' => 'number',
            ],
            'due-day' => [
                'label' => 'Payment deadline (no. of days)',
                'values' => null,
                'type' => 'number',
            ],
            'invoice-template' => [
                'label' => 'Invoice template',
                'values' => array_merge([
                    'default' => 'Default',
                ], static::getInvoiceTemplates()),
            ],
        ];

        return $map;
    }

    /**
     * Rent invoice templates.
     *
     * @return static
     */
    protected static function getInvoiceTemplates()
    {
        $cmsPath = resource_path('views/invoices/rent');

        $layouts = collect(File::files($cmsPath, true))->mapWithKeys(function ($layout, $key) {
            $name = str_replace(".blade.php", '', $layout->getFilename());

            return [$name => $name];
        });

        return $layouts->toArray();
    }
}